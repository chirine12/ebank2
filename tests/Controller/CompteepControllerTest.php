<?php

namespace App\Test\Controller;

use App\Entity\Compteep;
use App\Repository\CompteepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompteepControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CompteepRepository $repository;
    private string $path = '/compteep/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Compteep::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Compteep index');

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
            'compteep[Rib]' => 'Testing',
            'compteep[solde]' => 'Testing',
            'compteep[type]' => 'Testing',
            'compteep[dateouv]' => 'Testing',
            'compteep[description]' => 'Testing',
            'compteep[client]' => 'Testing',
        ]);

        self::assertResponseRedirects('/compteep/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Compteep();
        $fixture->setRib('My Title');
        $fixture->setSolde('My Title');
        $fixture->setType('My Title');
        $fixture->setDateouv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Compteep');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Compteep();
        $fixture->setRib('My Title');
        $fixture->setSolde('My Title');
        $fixture->setType('My Title');
        $fixture->setDateouv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'compteep[Rib]' => 'Something New',
            'compteep[solde]' => 'Something New',
            'compteep[type]' => 'Something New',
            'compteep[dateouv]' => 'Something New',
            'compteep[description]' => 'Something New',
            'compteep[client]' => 'Something New',
        ]);

        self::assertResponseRedirects('/compteep/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRib());
        self::assertSame('Something New', $fixture[0]->getSolde());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getDateouv());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getClient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Compteep();
        $fixture->setRib('My Title');
        $fixture->setSolde('My Title');
        $fixture->setType('My Title');
        $fixture->setDateouv('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/compteep/');
    }
}
