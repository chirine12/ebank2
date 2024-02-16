<?php

namespace App\Test\Controller;

use App\Entity\Carte;
use App\Repository\CarteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CarteRepository $repository;
    private string $path = '/carte/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Carte::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Carte index');

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
            'carte[num]' => 'Testing',
            'carte[nom]' => 'Testing',
            'carte[dateexp]' => 'Testing',
            'carte[cvv]' => 'Testing',
        ]);

        self::assertResponseRedirects('/carte/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Carte();
        $fixture->setNum('My Title');
        $fixture->setNom('My Title');
        $fixture->setDateexp('My Title');
        $fixture->setCvv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Carte');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Carte();
        $fixture->setNum('My Title');
        $fixture->setNom('My Title');
        $fixture->setDateexp('My Title');
        $fixture->setCvv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'carte[num]' => 'Something New',
            'carte[nom]' => 'Something New',
            'carte[dateexp]' => 'Something New',
            'carte[cvv]' => 'Something New',
        ]);

        self::assertResponseRedirects('/carte/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNum());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getDateexp());
        self::assertSame('Something New', $fixture[0]->getCvv());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Carte();
        $fixture->setNum('My Title');
        $fixture->setNom('My Title');
        $fixture->setDateexp('My Title');
        $fixture->setCvv('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/carte/');
    }
}
