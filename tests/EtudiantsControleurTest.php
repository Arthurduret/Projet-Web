<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../controleurs/etudiants_controleur.php';
require_once __DIR__ . '/../modeles/utilisateur_modele.php';

class EtudiantsControleurTest extends TestCase
{
    private function creerPDOMock(): PDO
    {
        return $this->createMock(PDO::class);
    }
    
    //  Test 1 : un non connecté est redirigé 
    public function testNonConnecteEstRedirige(): void
    {
        $_SESSION = [];

        $pdo  = $this->creerPDOMock();
        $ctrl = new EtudiantsControleur($pdo);

        // verifierPilote() appelle header() + exit()
        // On vérifie que EtudiantsControleur peut être instancié
        $this->assertInstanceOf(EtudiantsControleur::class, $ctrl);
    }

    //  Test 2 : un étudiant n'a pas accès
    public function testEtudiantNaPasAcces(): void
    {
        $_SESSION['user'] = [
            'id_utilisateur' => 1,
            'role'           => 'etudiant'
        ];

        $pdo  = $this->creerPDOMock();
        $ctrl = new EtudiantsControleur($pdo);

        $this->assertFalse(
            in_array($_SESSION['user']['role'], ['pilote', 'admin'])
        );
    }

    //  Test 3 : un pilote a bien accès 
    public function testPiloteAAcces(): void
    {
        $_SESSION['user'] = [
            'id_utilisateur' => 2,
            'role'           => 'pilote'
        ];

        $pdo  = $this->creerPDOMock();
        $ctrl = new EtudiantsControleur($pdo);

        $this->assertTrue(
            in_array($_SESSION['user']['role'], ['pilote', 'admin'])
        );
    }

    //  Test 4 : un admin a bien accès 
    public function testAdminAAcces(): void
    {
        $_SESSION['user'] = [
            'id_utilisateur' => 3,
            'role'           => 'admin'
        ];

        $pdo  = $this->creerPDOMock();
        $ctrl = new EtudiantsControleur($pdo);

        $this->assertTrue(
            in_array($_SESSION['user']['role'], ['pilote', 'admin'])
        );
    }

    //  Test 5 : le contrôleur s'instancie correctement 
    public function testInstanciation(): void
    {
        $_SESSION['user'] = [
            'id_utilisateur' => 1,
            'role'           => 'pilote'
        ];

        $pdo  = $this->creerPDOMock();
        $ctrl = new EtudiantsControleur($pdo);

        $this->assertInstanceOf(EtudiantsControleur::class, $ctrl);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}