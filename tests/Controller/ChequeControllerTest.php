<?php

namespace App\Test\Controller;

use App\Entity\Cheque;
use App\Repository\ChequeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChequeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ChequeRepository $repository;
    private string $path = '/cheque/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Cheque::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cheque index');

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
            'cheque[num]' => 'Testing',
            'cheque[numcompte]' => 'Testing',
            'cheque[montant]' => 'Testing',
            'cheque[signature]' => 'Testing',
            'cheque[date]' => 'Testing',
        ]);

        self::assertResponseRedirects('/cheque/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cheque();
        $fixture->setNum('My Title');
        $fixture->setNumcompte('My Title');
        $fixture->setMontant('My Title');
        $fixture->setSignature('My Title');
        $fixture->setDate('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cheque');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cheque();
        $fixture->setNum('My Title');
        $fixture->setNumcompte('My Title');
        $fixture->setMontant('My Title');
        $fixture->setSignature('My Title');
        $fixture->setDate('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cheque[num]' => 'Something New',
            'cheque[numcompte]' => 'Something New',
            'cheque[montant]' => 'Something New',
            'cheque[signature]' => 'Something New',
            'cheque[date]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cheque/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNum());
        self::assertSame('Something New', $fixture[0]->getNumcompte());
        self::assertSame('Something New', $fixture[0]->getMontant());
        self::assertSame('Something New', $fixture[0]->getSignature());
        self::assertSame('Something New', $fixture[0]->getDate());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Cheque();
        $fixture->setNum('My Title');
        $fixture->setNumcompte('My Title');
        $fixture->setMontant('My Title');
        $fixture->setSignature('My Title');
        $fixture->setDate('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/cheque/');
    }
}
