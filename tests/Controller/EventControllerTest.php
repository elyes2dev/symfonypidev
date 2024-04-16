<?php

namespace App\Test\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EventRepository $repository;
    private string $path = '/event/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Event::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'event[name]' => 'Testing',
            'event[datedeb]' => 'Testing',
            'event[datefin]' => 'Testing',
            'event[starttime]' => 'Testing',
            'event[endtime]' => 'Testing',
            'event[nbrparticipants]' => 'Testing',
            'event[price]' => 'Testing',
            'event[idclub]' => 'Testing',
            'event[idimage]' => 'Testing',
            'event[idplayer]' => 'Testing',
            'event[idpayment]' => 'Testing',
        ]);

        self::assertResponseRedirects('/event/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Event();
        $fixture->setName('My Title');
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setNbrparticipants('My Title');
        $fixture->setPrice('My Title');
        $fixture->setIdclub('My Title');
        $fixture->setIdimage('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Event();
        $fixture->setName('My Title');
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setNbrparticipants('My Title');
        $fixture->setPrice('My Title');
        $fixture->setIdclub('My Title');
        $fixture->setIdimage('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'event[name]' => 'Something New',
            'event[datedeb]' => 'Something New',
            'event[datefin]' => 'Something New',
            'event[starttime]' => 'Something New',
            'event[endtime]' => 'Something New',
            'event[nbrparticipants]' => 'Something New',
            'event[price]' => 'Something New',
            'event[idclub]' => 'Something New',
            'event[idimage]' => 'Something New',
            'event[idplayer]' => 'Something New',
            'event[idpayment]' => 'Something New',
        ]);

        self::assertResponseRedirects('/event/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDatedeb());
        self::assertSame('Something New', $fixture[0]->getDatefin());
        self::assertSame('Something New', $fixture[0]->getStarttime());
        self::assertSame('Something New', $fixture[0]->getEndtime());
        self::assertSame('Something New', $fixture[0]->getNbrparticipants());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getIdclub());
        self::assertSame('Something New', $fixture[0]->getIdimage());
        self::assertSame('Something New', $fixture[0]->getIdplayer());
        self::assertSame('Something New', $fixture[0]->getIdpayment());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Event();
        $fixture->setName('My Title');
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setNbrparticipants('My Title');
        $fixture->setPrice('My Title');
        $fixture->setIdclub('My Title');
        $fixture->setIdimage('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/event/');
    }
}
