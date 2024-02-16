<?php

namespace App\Test\Controller;

use App\Entity\Contrat;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContratControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContratRepository $repository;
    private string $path = '/contrat/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Contrat::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contrat index');

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
            'contrat[datedeb]' => 'Testing',
            'contrat[datefin]' => 'Testing',
            'contrat[signature]' => 'Testing',
            'contrat[type]' => 'Testing',
            'contrat[client]' => 'Testing',
        ]);

        self::assertResponseRedirects('/contrat/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contrat();
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setSignature('My Title');
        $fixture->setType('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contrat');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contrat();
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setSignature('My Title');
        $fixture->setType('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'contrat[datedeb]' => 'Something New',
            'contrat[datefin]' => 'Something New',
            'contrat[signature]' => 'Something New',
            'contrat[type]' => 'Something New',
            'contrat[client]' => 'Something New',
        ]);

        self::assertResponseRedirects('/contrat/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDatedeb());
        self::assertSame('Something New', $fixture[0]->getDatefin());
        self::assertSame('Something New', $fixture[0]->getSignature());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getClient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Contrat();
        $fixture->setDatedeb('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setSignature('My Title');
        $fixture->setType('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/contrat/');
    }
}
