<?php

namespace App\Test\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TacheControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TacheRepository $repository;
    private string $path = '/tache/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Tache::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tache index');

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
            'tache[titre]' => 'Testing',
            'tache[description]' => 'Testing',
            'tache[dat_limite]' => 'Testing',
            'tache[statut]' => 'Testing',
            'tache[utulisateur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tache/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tache();
        $fixture->setTitre('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDat_limite('My Title');
        $fixture->setStatut('My Title');
        $fixture->setUtulisateur('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tache');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tache();
        $fixture->setTitre('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDat_limite('My Title');
        $fixture->setStatut('My Title');
        $fixture->setUtulisateur('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tache[titre]' => 'Something New',
            'tache[description]' => 'Something New',
            'tache[dat_limite]' => 'Something New',
            'tache[statut]' => 'Something New',
            'tache[utulisateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/tache/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDat_limite());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getUtulisateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Tache();
        $fixture->setTitre('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDat_limite('My Title');
        $fixture->setStatut('My Title');
        $fixture->setUtulisateur('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tache/');
    }
}
