<?php

namespace App\DataFixtures;

use App\Entity\Torneos;
use App\Entity\EquipoTorneo;
use App\Entity\Grupo;
use App\Entity\EquipoGrupo;
use App\Entity\PartidoGrupo;
use App\Entity\CruceFinal;
use App\Entity\PartidoFinal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TorneoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $torneo = new Torneos();
        $torneo->setNombre('Torneo Verano 2024');
        $torneo->setCodigoAcceso('abc123');
        $torneo->setSlug('torneo_verano_2024');
        $manager->persist($torneo);

        // Equipos y Grupos
        $grupoA = new Grupo();
        $grupoA->setNombre('Grupo A');
        $grupoA->setTorneo($torneo);
        $manager->persist($grupoA);

        $grupoB = new Grupo();
        $grupoB->setNombre('Grupo B');
        $grupoB->setTorneo($torneo);
        $manager->persist($grupoB);

        $equipos = [];

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Alevín A');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Alevín A'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoA);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Alevín B');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Alevín B'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoA);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Benjamín A');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Benjamín A'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoA);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Benjamín B');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Benjamín B'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoA);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Infantil A');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Infantil A'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoB);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Infantil B');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Infantil B'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoB);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Cadete A');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Cadete A'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoB);
        $manager->persist($eg);

        $equipo = new EquipoTorneo();
        $equipo->setNombre('Cadete B');
        $equipo->setTorneo($torneo);
        $manager->persist($equipo);
        $equipos['Cadete B'] = $equipo;

        $eg = new EquipoGrupo();
        $eg->setEquipo($equipo);
        $eg->setGrupo($grupoB);
        $manager->persist($eg);

        // Partidos Grupo A

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Alevín A']);
        $pg->setVisitante($equipos['Benjamín A']);
        $pg->setGolesLocal(2);
        $pg->setGolesVisitante(0);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Alevín B']);
        $pg->setVisitante($equipos['Benjamín B']);
        $pg->setGolesLocal(1);
        $pg->setGolesVisitante(1);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Alevín A']);
        $pg->setVisitante($equipos['Alevín B']);
        $pg->setGolesLocal(0);
        $pg->setGolesVisitante(1);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Benjamín A']);
        $pg->setVisitante($equipos['Benjamín B']);
        $pg->setGolesLocal(3);
        $pg->setGolesVisitante(2);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Alevín A']);
        $pg->setVisitante($equipos['Benjamín B']);
        $pg->setGolesLocal(1);
        $pg->setGolesVisitante(1);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoA);
        $pg->setLocal($equipos['Benjamín A']);
        $pg->setVisitante($equipos['Alevín B']);
        $pg->setGolesLocal(0);
        $pg->setGolesVisitante(2);
        $manager->persist($pg);

        // Partidos Grupo B

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Infantil A']);
        $pg->setVisitante($equipos['Cadete A']);
        $pg->setGolesLocal(0);
        $pg->setGolesVisitante(1);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Infantil B']);
        $pg->setVisitante($equipos['Cadete B']);
        $pg->setGolesLocal(2);
        $pg->setGolesVisitante(2);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Infantil A']);
        $pg->setVisitante($equipos['Infantil B']);
        $pg->setGolesLocal(1);
        $pg->setGolesVisitante(0);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Cadete A']);
        $pg->setVisitante($equipos['Cadete B']);
        $pg->setGolesLocal(2);
        $pg->setGolesVisitante(1);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Infantil A']);
        $pg->setVisitante($equipos['Cadete B']);
        $pg->setGolesLocal(0);
        $pg->setGolesVisitante(3);
        $manager->persist($pg);

        $pg = new PartidoGrupo();
        $pg->setGrupo($grupoB);
        $pg->setLocal($equipos['Infantil B']);
        $pg->setVisitante($equipos['Cadete A']);
        $pg->setGolesLocal(1);
        $pg->setGolesVisitante(4);
        $manager->persist($pg);

        // Semifinales
        $sf1 = new CruceFinal();
        $sf1->setFase('Semifinal 1');
        $sf1->setTorneo($torneo);
        $manager->persist($sf1);

        $sf2 = new CruceFinal();
        $sf2->setFase('Semifinal 2');
        $sf2->setTorneo($torneo);
        $manager->persist($sf2);

        $pf = new PartidoFinal();
        $pf->setCruceFinal($sf1);
        $pf->setLocal($equipos['Alevín B']);
        $pf->setVisitante($equipos['Cadete B']);
        $pf->setGolesLocal(1);
        $pf->setGolesVisitante(0);
        $manager->persist($pf);

        $pf = new PartidoFinal();
        $pf->setCruceFinal($sf2);
        $pf->setLocal($equipos['Cadete A']);
        $pf->setVisitante($equipos['Benjamín A']);
        $pf->setGolesLocal(3);
        $pf->setGolesVisitante(2);
        $manager->persist($pf);

        // Final
        $final = new CruceFinal();
        $final->setFase('Final');
        $final->setTorneo($torneo);
        $manager->persist($final);

        $pf = new PartidoFinal();
        $pf->setCruceFinal($final);
        $pf->setLocal($equipos['Alevín B']);
        $pf->setVisitante($equipos['Cadete A']);
        $pf->setGolesLocal(2);
        $pf->setGolesVisitante(2);
        $pf->setPenalties('5-4');
        $manager->persist($pf);

        // Tercer Puesto
        $tp = new CruceFinal();
        $tp->setFase('3º y 4º Puesto');
        $tp->setTorneo($torneo);
        $manager->persist($tp);

        $pf = new PartidoFinal();
        $pf->setCruceFinal($tp);
        $pf->setLocal($equipos['Cadete B']);
        $pf->setVisitante($equipos['Benjamín A']);
        $pf->setGolesLocal(1);
        $pf->setGolesVisitante(2);
        $manager->persist($pf);

        $manager->flush();
    }
}