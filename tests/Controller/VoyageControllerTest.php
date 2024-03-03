<?php

namespace App\Test\Controller;

use App\Entity\Voyage;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoyageControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private VoyageRepository $repository;
    private string $path = '/voyage/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Voyage::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voyage index');

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
            'voyage[villeDepart]' => 'Testing',
            'voyage[destination]' => 'Testing',
            'voyage[heureDepart]' => 'Testing',
            'voyage[heureArrivee]' => 'Testing',
            'voyage[typeVoyage]' => 'Testing',
            'voyage[type]' => 'Testing',
        ]);

        self::assertResponseRedirects('/voyage/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voyage();
        $fixture->setVilleDepart('My Title');
        $fixture->setDestination('My Title');
        $fixture->setHeureDepart('My Title');
        $fixture->setHeureArrivee('My Title');
        $fixture->setTypeVoyage('My Title');
        $fixture->setType('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voyage');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voyage();
        $fixture->setVilleDepart('My Title');
        $fixture->setDestination('My Title');
        $fixture->setHeureDepart('My Title');
        $fixture->setHeureArrivee('My Title');
        $fixture->setTypeVoyage('My Title');
        $fixture->setType('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voyage[villeDepart]' => 'Something New',
            'voyage[destination]' => 'Something New',
            'voyage[heureDepart]' => 'Something New',
            'voyage[heureArrivee]' => 'Something New',
            'voyage[typeVoyage]' => 'Something New',
            'voyage[type]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voyage/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getVilleDepart());
        self::assertSame('Something New', $fixture[0]->getDestination());
        self::assertSame('Something New', $fixture[0]->getHeureDepart());
        self::assertSame('Something New', $fixture[0]->getHeureArrivee());
        self::assertSame('Something New', $fixture[0]->getTypeVoyage());
        self::assertSame('Something New', $fixture[0]->getType());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Voyage();
        $fixture->setVilleDepart('My Title');
        $fixture->setDestination('My Title');
        $fixture->setHeureDepart('My Title');
        $fixture->setHeureArrivee('My Title');
        $fixture->setTypeVoyage('My Title');
        $fixture->setType('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/voyage/');
    }
}
