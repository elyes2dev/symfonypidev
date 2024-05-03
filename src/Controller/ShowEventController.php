<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class ShowEventController extends AbstractController
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    #[Route('/ShowEvent', name: 'ShowEvent')]
    public function index(Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $date = $request->query->get('date');

        // Call the search function with the search term and date
        $events = $this->eventRepository->search($searchTerm, $date);

        return $this->render('dashboard/ShowEvent.html.twig', [
            'events' => $events,
            'searchTerm' => $searchTerm,
            'date' => $date,
            'generateRandomColor' => [$this, 'generateRandomColor'], // Pass the function as a parameter
        ]);
    }


    #[Route('/ShowStatistics', name: 'ShowStatistics')]
    public function pieChart(EventRepository $eventRepository): Response
    {
        // 1. Extract data from the database
        $eventData = $eventRepository->getEventData();

        // 2. Prepare data for the chart
        $labels = [];
        $data = [];

        // Total count of all events
        $totalEvents = array_sum(array_column($eventData, 'count'));

        // Group the data by event name and calculate percentages based on the count of events for each number of participants
        $groupedEventData = [];
        foreach ($eventData as $event) {
            $name = $event['name'];
            $nbrParticipants = $event['nbrParticipants'];
            $count = $event['nbrParticipants'];

            if (!isset($groupedEventData[$name])) {
                $groupedEventData[$name] = [];
            }

            if (!isset($groupedEventData[$name][$nbrParticipants])) {
                $groupedEventData[$name][$nbrParticipants] = 0;
            }

            $groupedEventData[$name][$nbrParticipants] += $count;
        }

        // Calculate percentages and prepare labels and data
        foreach ($groupedEventData as $name => $participantsData) {
            foreach ($participantsData as $nbrParticipants => $count) {
                $labels[] = "$name ($nbrParticipants participants)";
                $percentage = ($count / $totalEvents) * 100;
                $data[] = round($percentage, 2);
            }
        }

        // 3. Render the chart
        return $this->render('dashboard/ShowStatistics.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }


}



