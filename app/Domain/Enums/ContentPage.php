<?php

namespace App\Domain\Enums;

enum ContentPage: string
{
    case Home = 'home';
    case About = 'about';
    case Centres = 'centres';
    case CentreEcoleDePolice = 'centre_ecole_de_police';
    case CentreNomayos = 'centre_nomayos';
    case Services = 'services';
    case TechnicalInspection = 'technical_inspection';
    case Fees = 'fees';
    case Appointment = 'appointment';
    case RoadSafety = 'road_safety';
    case Contact = 'contact';
}
