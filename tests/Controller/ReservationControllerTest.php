<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservation/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

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
            'reservation[date]' => 'Testing',
            'reservation[starttime]' => 'Testing',
            'reservation[endtime]' => 'Testing',
            'reservation[type]' => 'Testing',
            'reservation[idplayer]' => 'Testing',
            'reservation[refstadium]' => 'Testing',
            'reservation[idpayment]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDate('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setType('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setRefstadium('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDate('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setType('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setRefstadium('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[date]' => 'Something New',
            'reservation[starttime]' => 'Something New',
            'reservation[endtime]' => 'Something New',
            'reservation[type]' => 'Something New',
            'reservation[idplayer]' => 'Something New',
            'reservation[refstadium]' => 'Something New',
            'reservation[idpayment]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getStarttime());
        self::assertSame('Something New', $fixture[0]->getEndtime());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getIdplayer());
        self::assertSame('Something New', $fixture[0]->getRefstadium());
        self::assertSame('Something New', $fixture[0]->getIdpayment());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservation();
        $fixture->setDate('My Title');
        $fixture->setStarttime('My Title');
        $fixture->setEndtime('My Title');
        $fixture->setType('My Title');
        $fixture->setIdplayer('My Title');
        $fixture->setRefstadium('My Title');
        $fixture->setIdpayment('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservation/');
    }
}
