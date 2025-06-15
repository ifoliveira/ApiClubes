<?php
        namespace App\DataFixtures;

        use App\Entity\Torneos;
        use App\Entity\EquipoTorneo;
        use App\Entity\Grupo;
        use App\Entity\EquipoGrupo;
        use App\Entity\PartidoFinal;
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
                $equipo->setNombre('A.D. LLOREDA');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['A.D. LLOREDA'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('C.D. GRUJOAN');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['C.D. GRUJOAN'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('C.D.MANUEL RUBIO');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['C.D.MANUEL RUBIO'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoA);
                $manager->persist($eg);
        
                // Equipos Grupo B
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('VERIÑA C.F.');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['VERIÑA C.F.'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('ASUNCION C.F.');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['ASUNCION C.F.'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                $equipo = new EquipoTorneo();
                $equipo->setNombre('NAVARRO C.F.');
                $equipo->setTorneo($torneo);
                $manager->persist($equipo);
                $equipos['NAVARRO C.F.'] = $equipo;
        
                $eg = new EquipoGrupo();
                $eg->setEquipo($equipo);
                $eg->setGrupo($grupoB);
                $manager->persist($eg);
        
                
                // Partidos Grupo A
        
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['A.D. LLOREDA']);
                $pg->setVisitante($equipos['C.D. GRUJOAN']);
                $pg->setFecha(new \DateTime("2025-06-14 10:00:00"));

                $pg->setLocalizacion('Campo1');
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['C.D.MANUEL RUBIO']);
                $pg->setVisitante($equipos['A.D. LLOREDA']);
                $pg->setFecha(new \DateTime("2025-06-14 10:30:00"));
                $pg->setLocalizacion('Campo1');                
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);          
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoA);
                $pg->setLocal($equipos['C.D.MANUEL RUBIO']);
                $pg->setVisitante($equipos['C.D. GRUJOAN']);
                $pg->setFecha(new \DateTime("2025-06-14 11:00:00"));
                $pg->setLocalizacion('Campo1');                
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);                         

        
                // Partidos Grupo B
        
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['ASUNCION C.F.']);
                $pg->setVisitante($equipos['NAVARRO C.F.']);
                $pg->setFecha(new \DateTime("2025-06-14 10:00:00"));
                $pg->setLocalizacion('Campo2');                
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['VERIÑA C.F.']);
                $pg->setVisitante($equipos['NAVARRO C.F.']);
                $pg->setFecha(new \DateTime("2025-06-14 10:30:00"));
                $pg->setLocalizacion('Campo2');                
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);
                $pg = new \App\Entity\PartidoGrupo();
                $pg->setGrupo($grupoB);
                $pg->setLocal($equipos['VERIÑA C.F.']);
                $pg->setVisitante($equipos['ASUNCION C.F.']);
                $pg->setFecha(new \DateTime("2025-06-14 11:00:00"));
                $pg->setLocalizacion('Campo2');                
                $pg->setGolesLocal(null);
                $pg->setGolesVisitante(null);
                $manager->persist($pg);                                

                // 1. Crear Semifinales y hacer flush para obtener IDs
                $sf1 = new PartidoFinal();
                $sf1->setTorneo($torneo);
                $sf1->setAliasLocal('A1');
                $sf1->setAliasVisitante('B2');
                $sf1->setFase('semifinal');
                $sf1->setFecha(new \DateTime("2025-06-14 11:30:00"));
                $sf1->setLocalizacion('Campo1');
                $manager->persist($sf1);

                $sf2 = new PartidoFinal();
                $sf2->setTorneo($torneo);
                $sf2->setAliasLocal('B1');
                $sf2->setAliasVisitante('A2');
                $sf2->setFase('semifinal');
                $sf2->setFecha(new \DateTime("2025-06-14 11:30:00"));
                $sf2->setLocalizacion('Campo2');
                $manager->persist($sf2);

                // Hacemos flush aquí para tener los IDs disponibles
                $manager->flush();
                // 2. Crear Final y Tercer Puesto con alias dinámicos
                $final = new PartidoFinal();
                $final->setTorneo($torneo);
                $final->setAliasLocal('WINNER-' . $sf1->getId());
                $final->setAliasVisitante('WINNER-' . $sf2->getId());
                $final->setFase('final');
                $final->setFecha(new \DateTime("2025-06-14 12:30:00"));
                $final->setLocalizacion('Campo1');
                $manager->persist($final);

                $tercero = new PartidoFinal();
                $tercero->setTorneo($torneo);
                $tercero->setAliasLocal('LOSER-' . $sf1->getId());
                $tercero->setAliasVisitante('LOSER-' . $sf2->getId());
                $tercero->setFase('tercer_puesto');
                $tercero->setFecha(new \DateTime("2025-06-14 12:30:00"));
                $tercero->setLocalizacion('Campo2');
                $manager->persist($tercero);

                // Guardar todo
                $manager->flush();

            }
        }