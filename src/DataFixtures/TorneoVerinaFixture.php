<?php
        namespace App\DataFixtures;

        use App\Entity\Torneos;
        use App\Entity\EquipoTorneo;
        use App\Entity\Grupo;
        use App\Entity\EquipoGrupo;
        use Doctrine\Bundle\FixturesBundle\Fixture;
        use Doctrine\Persistence\ObjectManager;
        
        class TorneoVerinaFixture extends Fixture
        {
            public function load(ObjectManager $manager): void
            {
                $torneo = new Torneos();
                $torneo->setNombre('Torneo Veriña C.F. Alevín Fútbol 11');
                $torneo->setCodigoAcceso('verina2024');
                $torneo->setSlug('verina_alevin_futbol_11');
                $manager->persist($torneo);
        
                $grupoA = new Grupo();
                $grupoA->setNombre('Grupo A');
                $grupoA->setTorneo($torneo);
                $manager->persist($grupoA);
        
                $grupoB = new Grupo();
                $grupoB->setNombre('Grupo B');
                $grupoB->setTorneo($torneo);
                $manager->persist($grupoB);
        
                $equipos = [];
        
                // Equipos Grupo A
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('A.D LLOREDA');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['A.D LLOREDA'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('CD GRUJOAN');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['CD GRUJOAN'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('C.D MANUEL RUBIO');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['C.D MANUEL RUBIO'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                // Equipos Grupo B
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('VERIÑA C.F');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['VERIÑA C.F'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('ASUNCION C.F');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['ASUNCION C.F'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('NAVARRO C.F');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['NAVARRO C.F'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                
                // Partidos Grupo A
        
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['A.D LLOREDA']);
                $pg->setVisitante($equipos['CD GRUJOAN']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['C.D MANUEL RUBIO']);
                $pg->setVisitante($equipos['A.D LLOREDA']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);          
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['C.D MANUEL RUBIO']);
                $pg->setVisitante($equipos['CD GRUJOAN']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);                         

        
                // Partidos Grupo B
        
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['ASUNCION C.F']);
                $pg->setVisitante($equipos['NAVARRO C.F']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['VERIÑA C.F']);
                $pg->setVisitante($equipos['NAVARRO C.F']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['VERIÑA C.F']);
                $pg->setVisitante($equipos['ASUNCION C.F']);
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);                                

        
                $manager->flush();
            }
        }