<?php

namespace App\Test\Controller;

use App\Entity\Utulisateur;
use App\Repository\UtulisateurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtulisateurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UtulisateurRepository $repository;
    private string $path = '/utulisateur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Utulisateur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utulisateur index');

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
            'utulisateur[nom]' => 'Testing',
            'utulisateur[adresse_email]' => 'Testing',
            'utulisateur[met_passe]' => 'Testing',
        ]);

        self::assertResponseRedirects('/utulisateur/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utulisateur();
        $fixture->setNom('My Title');
        $fixture->setAdresse_email('My Title');
        $fixture->setMet_passe('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utulisateur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utulisateur();
        $fixture->setNom('My Title');
        $fixture->setAdresse_email('My Title');
        $fixture->setMet_passe('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'utulisateur[nom]' => 'Something New',
            'utulisateur[adresse_email]' => 'Something New',
            'utulisateur[met_passe]' => 'Something New',
        ]);

        self::assertResponseRedirects('/utulisateur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getAdresse_email());
        self::assertSame('Something New', $fixture[0]->getMet_passe());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Utulisateur();
        $fixture->setNom('My Title');
        $fixture->setAdresse_email('My Title');
        $fixture->setMet_passe('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/utulisateur/');
    }
}
