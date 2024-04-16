<?php

namespace App\Test\Controller;

use App\Entity\TaskEvent;
use App\Repository\TaskEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskEventControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TaskEventRepository $repository;
    private string $path = '/task/event/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(TaskEvent::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TaskEvent index');

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
            'task_event[Description]' => 'Testing',
            'task_event[Etat]' => 'Testing',
            'task_event[CreationDate]' => 'Testing',
            'task_event[UpdatedDate]' => 'Testing',
            'task_event[EventId]' => 'Testing',
        ]);

        self::assertResponseRedirects('/task/event/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new TaskEvent();
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setUpdatedDate('My Title');
        $fixture->setEventId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TaskEvent');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new TaskEvent();
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setUpdatedDate('My Title');
        $fixture->setEventId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'task_event[Description]' => 'Something New',
            'task_event[Etat]' => 'Something New',
            'task_event[CreationDate]' => 'Something New',
            'task_event[UpdatedDate]' => 'Something New',
            'task_event[EventId]' => 'Something New',
        ]);

        self::assertResponseRedirects('/task/event/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getCreationDate());
        self::assertSame('Something New', $fixture[0]->getUpdatedDate());
        self::assertSame('Something New', $fixture[0]->getEventId());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new TaskEvent();
        $fixture->setDescription('My Title');
        $fixture->setEtat('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setUpdatedDate('My Title');
        $fixture->setEventId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/task/event/');
    }
}
