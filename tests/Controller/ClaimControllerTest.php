<?php

namespace App\Test\Controller;

use App\Entity\Claim;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClaimControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ClaimRepository $repository;
    private string $path = '/claim/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Claim::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Claim index');

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
            'claim[date]' => 'Testing',
            'claim[description]' => 'Testing',
            'claim[type]' => 'Testing',
            'claim[status]' => 'Testing',
            'claim[satisfaction]' => 'Testing',
            'claim[image]' => 'Testing',
            'claim[response]' => 'Testing',
            'claim[closuredate]' => 'Testing',
            'claim[iduser]' => 'Testing',
            'claim[idclub]' => 'Testing',
        ]);

        self::assertResponseRedirects('/claim/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Claim();
        $fixture->setDate('My Title');
        $fixture->setDescription('My Title');
        $fixture->setType('My Title');
        $fixture->setStatus('My Title');
        $fixture->setSatisfaction('My Title');
        $fixture->setImage('My Title');
        $fixture->setResponse('My Title');
        $fixture->setClosuredate('My Title');
        $fixture->setIduser('My Title');
        $fixture->setIdclub('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Claim');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Claim();
        $fixture->setDate('My Title');
        $fixture->setDescription('My Title');
        $fixture->setType('My Title');
        $fixture->setStatus('My Title');
        $fixture->setSatisfaction('My Title');
        $fixture->setImage('My Title');
        $fixture->setResponse('My Title');
        $fixture->setClosuredate('My Title');
        $fixture->setIduser('My Title');
        $fixture->setIdclub('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'claim[date]' => 'Something New',
            'claim[description]' => 'Something New',
            'claim[type]' => 'Something New',
            'claim[status]' => 'Something New',
            'claim[satisfaction]' => 'Something New',
            'claim[image]' => 'Something New',
            'claim[response]' => 'Something New',
            'claim[closuredate]' => 'Something New',
            'claim[iduser]' => 'Something New',
            'claim[idclub]' => 'Something New',
        ]);

        self::assertResponseRedirects('/claim/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getSatisfaction());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getResponse());
        self::assertSame('Something New', $fixture[0]->getClosuredate());
        self::assertSame('Something New', $fixture[0]->getIduser());
        self::assertSame('Something New', $fixture[0]->getIdclub());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Claim();
        $fixture->setDate('My Title');
        $fixture->setDescription('My Title');
        $fixture->setType('My Title');
        $fixture->setStatus('My Title');
        $fixture->setSatisfaction('My Title');
        $fixture->setImage('My Title');
        $fixture->setResponse('My Title');
        $fixture->setClosuredate('My Title');
        $fixture->setIduser('My Title');
        $fixture->setIdclub('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/claim/');
    }
}
