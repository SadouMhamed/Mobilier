import React, { useState, useEffect } from "react";
import {
    ArrowRightIcon,
    PhoneIcon,
    EnvelopeIcon,
    MapPinIcon,
    PlusIcon,
    Bars3Icon,
    XMarkIcon,
} from "@heroicons/react/24/outline";

// Header Component - Design minimaliste avec menu mobile
const Header = () => {
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 100);
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    // Fermer le menu mobile quand on clique sur un lien
    const handleMobileMenuClick = () => {
        setIsMobileMenuOpen(false);
    };

    return (
        <header
            className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
                isScrolled
                    ? "bg-white/95 backdrop-blur-lg shadow-sm border-b border-gray-100"
                    : "bg-transparent"
            }`}
        >
            <div className="px-6 mx-auto max-w-7xl lg:px-12">
                <div className="flex justify-between items-center h-24">
                    {/* Logo élégant */}
                    <div className="text-3xl font-light tracking-wider text-gray-900">
                        MOBILIER
                        <span className="text-sm font-normal text-gray-500 ml-1">
                            ALGÉRIE
                        </span>
                    </div>

                    {/* Navigation épurée - Desktop */}
                    <nav className="hidden items-center space-x-12 md:flex">
                        <a
                            href="#collection"
                            className="text-sm font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                        >
                            Collection
                        </a>
                        <a
                            href="#services"
                            className="text-sm font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                        >
                            Services
                        </a>
                        <a
                            href="#contact"
                            className="text-sm font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                        >
                            Contact
                        </a>
                    </nav>

                    {/* CTA minimaliste - Desktop */}
                    <div className="hidden md:flex items-center space-x-6">
                        <a
                            href="/login"
                            className="text-sm font-light text-gray-700 uppercase tracking-wide transition-colors hover:text-gray-900"
                        >
                            Connexion
                        </a>
                        <a
                            href="/register"
                            className="px-6 py-3 text-sm font-light tracking-wide text-white uppercase bg-gray-900 transition-all duration-300 hover:bg-gray-800"
                        >
                            S'inscrire
                        </a>
                    </div>

                    {/* Menu hamburger - Mobile */}
                    <button
                        className="md:hidden p-2 text-gray-700 hover:text-gray-900 transition-colors"
                        onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                        aria-label="Toggle menu"
                    >
                        {isMobileMenuOpen ? (
                            <XMarkIcon className="w-6 h-6" />
                        ) : (
                            <Bars3Icon className="w-6 h-6" />
                        )}
                    </button>
                </div>

                {/* Menu mobile déroulant */}
                {isMobileMenuOpen && (
                    <div className="md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg">
                        <nav className="px-6 py-8 space-y-6">
                            <a
                                href="#collection"
                                onClick={handleMobileMenuClick}
                                className="block text-lg font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                            >
                                Collection
                            </a>
                            <a
                                href="#services"
                                onClick={handleMobileMenuClick}
                                className="block text-lg font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                            >
                                Services
                            </a>
                            <a
                                href="#contact"
                                onClick={handleMobileMenuClick}
                                className="block text-lg font-light tracking-wide text-gray-700 uppercase transition-colors hover:text-gray-900"
                            >
                                Contact
                            </a>

                            {/* Séparateur */}
                            <div className="border-t border-gray-200 pt-6 space-y-4">
                                <a
                                    href="/login"
                                    onClick={handleMobileMenuClick}
                                    className="block text-sm font-light text-gray-700 uppercase tracking-wide transition-colors hover:text-gray-900"
                                >
                                    Connexion
                                </a>
                                <a
                                    href="/register"
                                    onClick={handleMobileMenuClick}
                                    className="block w-full px-6 py-3 text-sm font-light tracking-wide text-white text-center uppercase bg-gray-900 transition-all duration-300 hover:bg-gray-800"
                                >
                                    S'inscrire
                                </a>
                            </div>
                        </nav>
                    </div>
                )}
            </div>
        </header>
    );
};

// Hero Section - Style épuré inspiré Prevel
const HeroSection = () => {
    return (
        <section className="relative min-h-screen bg-white">
            {/* Layout asymétrique avec image */}
            <div className="grid grid-cols-1 lg:grid-cols-2 min-h-screen">
                {/* Contenu textuel - Côté gauche */}
                <div className="flex items-center justify-center px-6 lg:px-12 xl:px-20">
                    <div className="max-w-lg space-y-12">
                        {/* Subtitle élégant */}
                        <div className="space-y-2">
                            <p className="text-xs font-light tracking-[0.3em] text-gray-500 uppercase">
                                Immobilier d'exception
                            </p>
                            <div className="w-12 h-px bg-gray-300"></div>
                        </div>

                        {/* Titre principal sophistiqué */}
                        <div className="space-y-6">
                            <h1 className="text-5xl xl:text-6xl font-extralight leading-tight text-gray-900 tracking-tight">
                                Cultiver l'art
                                <br />
                                <span className="font-light">de vivre</span>
                                <br />
                                <span className="italic font-light text-gray-600">
                                    ensemble
                                </span>
                            </h1>
                        </div>

                        {/* Description minimaliste */}
                        <p className="text-lg font-light leading-relaxed text-gray-600 max-w-md">
                            Découvrez notre sélection exclusive de propriétés
                            d'exception à travers l'Algérie. Une approche
                            sur-mesure depuis 15 ans.
                        </p>

                        {/* CTA élégant */}
                        <div className="space-y-4">
                            <a
                                href="/properties"
                                className="inline-flex items-center group"
                            >
                                <span className="text-sm font-light tracking-wide uppercase text-gray-900 border-b border-gray-300 pb-1 transition-all duration-300 group-hover:border-gray-900">
                                    Explorer notre collection
                                </span>
                                <ArrowRightIcon className="ml-4 w-4 h-4 text-gray-900 transition-transform duration-300 group-hover:translate-x-1" />
                            </a>
                        </div>
                    </div>
                </div>

                {/* Image architecturale - Côté droit */}
                <div className="relative overflow-hidden">
                    <img
                        src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                        alt="Architecture moderne"
                        className="object-cover w-full h-full transition-transform duration-700 hover:scale-105"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
            </div>

            {/* Indicateur de scroll minimaliste */}
            <div className="absolute bottom-12 left-1/2 transform -translate-x-1/2">
                <div className="flex flex-col items-center space-y-2">
                    <div className="w-px h-12 bg-gray-300 animate-pulse"></div>
                    <p className="text-xs font-light tracking-wider text-gray-500 uppercase rotate-90 whitespace-nowrap">
                        Découvrir
                    </p>
                </div>
            </div>
        </section>
    );
};

// Section Collection - Style galerie
const CollectionSection = () => {
    const properties = [
        {
            id: 1,
            title: "Villa contemporaine",
            location: "Hydra, Alger",
            price: "45M DZD",
            image: "https://images.unsplash.com/photo-1613490493576-7fde63acd811?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            features: ["5 chambres", "300m²", "Piscine"],
        },
        {
            id: 2,
            title: "Appartement de prestige",
            location: "Oran Centre",
            price: "28M DZD",
            image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            features: ["4 chambres", "180m²", "Vue mer"],
        },
        {
            id: 3,
            title: "Résidence familiale",
            location: "Constantine",
            price: "35M DZD",
            image: "https://images.unsplash.com/photo-1600585154526-990dced4db0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
            features: ["6 chambres", "250m²", "Jardin"],
        },
    ];

    return (
        <section id="collection" className="py-32 bg-gray-50">
            <div className="px-6 mx-auto max-w-7xl lg:px-12">
                {/* En-tête de section */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-20">
                    <div className="space-y-8">
                        <div className="space-y-4">
                            <p className="text-xs font-light tracking-[0.3em] text-gray-500 uppercase">
                                Notre collection
                            </p>
                            <h2 className="text-4xl xl:text-5xl font-extralight leading-tight text-gray-900">
                                Propriétés
                                <br />
                                <span className="italic">d'exception</span>
                            </h2>
                        </div>
                    </div>
                    <div className="flex items-end">
                        <p className="text-lg font-light text-gray-600 leading-relaxed max-w-md">
                            Chaque propriété est soigneusement sélectionnée pour
                            son architecture, son emplacement et son potentiel
                            d'investissement unique.
                        </p>
                    </div>
                </div>

                {/* Grille de propriétés */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {properties.map((property, index) => (
                        <div key={property.id} className="group cursor-pointer">
                            {/* Image */}
                            <div className="relative overflow-hidden mb-6 aspect-[4/5]">
                                <img
                                    src={property.image}
                                    alt={property.title}
                                    className="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110"
                                />
                                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500"></div>

                                {/* Price overlay */}
                                <div className="absolute top-6 right-6">
                                    <div className="bg-white/90 backdrop-blur-sm px-4 py-2">
                                        <p className="text-sm font-light text-gray-900">
                                            {property.price}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Informations */}
                            <div className="space-y-4">
                                <div>
                                    <h3 className="text-xl font-light text-gray-900 mb-1">
                                        {property.title}
                                    </h3>
                                    <p className="text-sm font-light text-gray-500 uppercase tracking-wide">
                                        {property.location}
                                    </p>
                                </div>

                                {/* Caractéristiques */}
                                <div className="flex space-x-4 text-xs text-gray-600">
                                    {property.features.map((feature, idx) => (
                                        <span
                                            key={idx}
                                            className="uppercase tracking-wide"
                                        >
                                            {feature}
                                        </span>
                                    ))}
                                </div>

                                {/* Lien discret */}
                                <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div className="flex items-center">
                                        <span className="text-xs font-light tracking-wide uppercase text-gray-900 border-b border-gray-300 pb-1">
                                            Découvrir
                                        </span>
                                        <ArrowRightIcon className="ml-2 w-3 h-3 text-gray-900" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* CTA pour voir plus */}
                <div className="flex justify-center mt-20">
                    <a
                        href="/properties"
                        className="inline-flex items-center group"
                    >
                        <span className="text-sm font-light tracking-wide uppercase text-gray-900 border-b border-gray-300 pb-1 transition-all duration-300 group-hover:border-gray-900">
                            Voir toute la collection
                        </span>
                        <ArrowRightIcon className="ml-4 w-4 h-4 text-gray-900 transition-transform duration-300 group-hover:translate-x-1" />
                    </a>
                </div>
            </div>
        </section>
    );
};

// Services Section - Design épuré
const ServicesSection = () => {
    const services = [
        {
            number: "01",
            title: "Acquisition",
            description:
                "Accompagnement personnalisé dans la recherche et l'acquisition de propriétés d'exception.",
            features: ["Conseil expert", "Négociation", "Suivi juridique"],
        },
        {
            number: "02",
            title: "Gestion",
            description:
                "Gestion complète de votre patrimoine immobilier avec une approche sur-mesure.",
            features: ["Locataires premium", "Maintenance", "Optimisation"],
        },
        {
            number: "03",
            title: "Investissement",
            description:
                "Stratégies d'investissement immobilier adaptées à vos objectifs patrimoniaux.",
            features: ["Analyse de marché", "Rentabilité", "Diversification"],
        },
    ];

    return (
        <section id="services" className="py-32 bg-white">
            <div className="px-6 mx-auto max-w-7xl lg:px-12">
                {/* En-tête */}
                <div className="mb-20 text-center">
                    <div className="space-y-4 mb-8">
                        <p className="text-xs font-light tracking-[0.3em] text-gray-500 uppercase">
                            Notre expertise
                        </p>
                        <h2 className="text-4xl xl:text-5xl font-extralight leading-tight text-gray-900">
                            Un service
                            <br />
                            <span className="italic">d'excellence</span>
                        </h2>
                    </div>
                    <div className="w-24 h-px bg-gray-300 mx-auto"></div>
                </div>

                {/* Services grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-16">
                    {services.map((service, index) => (
                        <div key={index} className="space-y-8">
                            {/* Numéro */}
                            <div className="text-6xl font-extralight text-gray-200">
                                {service.number}
                            </div>

                            {/* Contenu */}
                            <div className="space-y-6">
                                <h3 className="text-2xl font-light text-gray-900">
                                    {service.title}
                                </h3>

                                <p className="text-gray-600 font-light leading-relaxed">
                                    {service.description}
                                </p>

                                {/* Features */}
                                <div className="space-y-2">
                                    {service.features.map((feature, idx) => (
                                        <div
                                            key={idx}
                                            className="flex items-center"
                                        >
                                            <div className="w-1 h-1 bg-gray-400 rounded-full mr-3"></div>
                                            <span className="text-sm font-light text-gray-600 uppercase tracking-wide">
                                                {feature}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
};

// Contact Section - Minimaliste
const ContactSection = () => {
    return (
        <section id="contact" className="py-32 bg-gray-50">
            <div className="px-6 mx-auto max-w-7xl lg:px-12">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-20">
                    {/* Informations de contact */}
                    <div className="space-y-12">
                        <div className="space-y-4">
                            <p className="text-xs font-light tracking-[0.3em] text-gray-500 uppercase">
                                Nous contacter
                            </p>
                            <h2 className="text-4xl xl:text-5xl font-extralight leading-tight text-gray-900">
                                Prenons contact
                            </h2>
                        </div>

                        <div className="space-y-8">
                            <div className="space-y-6">
                                <div className="flex items-start space-x-4">
                                    <MapPinIcon className="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" />
                                    <div>
                                        <p className="text-sm font-light text-gray-900 mb-1">
                                            Adresse
                                        </p>
                                        <p className="text-sm font-light text-gray-600 leading-relaxed">
                                            Cité Universitaire
                                            <br />
                                            Bab Ezzouar, Alger 16111
                                            <br />
                                            Algérie
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start space-x-4">
                                    <PhoneIcon className="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" />
                                    <div>
                                        <p className="text-sm font-light text-gray-900 mb-1">
                                            Téléphone
                                        </p>
                                        <p className="text-sm font-light text-gray-600">
                                            +213 555 123 456
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start space-x-4">
                                    <EnvelopeIcon className="w-5 h-5 text-gray-400 mt-1 flex-shrink-0" />
                                    <div>
                                        <p className="text-sm font-light text-gray-900 mb-1">
                                            Email
                                        </p>
                                        <p className="text-sm font-light text-gray-600">
                                            contact@mobilier-algerie.dz
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Horaires */}
                            <div className="pt-8 border-t border-gray-200">
                                <p className="text-sm font-light text-gray-900 mb-4">
                                    Horaires d'ouverture
                                </p>
                                <div className="space-y-2 text-sm font-light text-gray-600">
                                    <div className="flex justify-between">
                                        <span>Dimanche - Jeudi</span>
                                        <span>09:00 - 18:00</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span>Vendredi - Samedi</span>
                                        <span>Fermé</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Formulaire de contact épuré */}
                    <div className="space-y-8">
                        <form className="space-y-6">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <input
                                        type="text"
                                        placeholder="Prénom"
                                        className="w-full px-0 py-4 text-gray-900 placeholder-gray-400 bg-transparent border-0 border-b border-gray-200 focus:border-gray-900 focus:ring-0 transition-colors duration-300"
                                    />
                                </div>
                                <div>
                                    <input
                                        type="text"
                                        placeholder="Nom"
                                        className="w-full px-0 py-4 text-gray-900 placeholder-gray-400 bg-transparent border-0 border-b border-gray-200 focus:border-gray-900 focus:ring-0 transition-colors duration-300"
                                    />
                                </div>
                            </div>

                            <div>
                                <input
                                    type="email"
                                    placeholder="Email"
                                    className="w-full px-0 py-4 text-gray-900 placeholder-gray-400 bg-transparent border-0 border-b border-gray-200 focus:border-gray-900 focus:ring-0 transition-colors duration-300"
                                />
                            </div>

                            <div>
                                <input
                                    type="tel"
                                    placeholder="Téléphone"
                                    className="w-full px-0 py-4 text-gray-900 placeholder-gray-400 bg-transparent border-0 border-b border-gray-200 focus:border-gray-900 focus:ring-0 transition-colors duration-300"
                                />
                            </div>

                            <div>
                                <textarea
                                    rows={4}
                                    placeholder="Message"
                                    className="w-full px-0 py-4 text-gray-900 placeholder-gray-400 bg-transparent border-0 border-b border-gray-200 focus:border-gray-900 focus:ring-0 resize-none transition-colors duration-300"
                                ></textarea>
                            </div>

                            <div className="pt-6">
                                <button
                                    type="submit"
                                    className="inline-flex items-center group"
                                >
                                    <span className="text-sm font-light tracking-wide uppercase text-gray-900 border-b border-gray-300 pb-1 transition-all duration-300 group-hover:border-gray-900">
                                        Envoyer le message
                                    </span>
                                    <ArrowRightIcon className="ml-4 w-4 h-4 text-gray-900 transition-transform duration-300 group-hover:translate-x-1" />
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    );
};

// Footer minimaliste
const Footer = () => {
    return (
        <footer className="bg-gray-900">
            <div className="px-6 mx-auto max-w-7xl lg:px-12">
                <div className="py-16">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-12">
                        {/* Brand */}
                        <div className="lg:col-span-2 space-y-6">
                            <div className="text-3xl font-light tracking-wider text-white">
                                MOBILIER
                                <span className="text-sm font-normal text-gray-400 ml-1">
                                    ALGÉRIE
                                </span>
                            </div>
                            <p className="text-gray-400 font-light leading-relaxed max-w-md">
                                Excellence immobilière en Algérie depuis plus de
                                15 ans. Une approche sur-mesure pour chaque
                                client.
                            </p>
                        </div>

                        {/* Services */}
                        <div className="space-y-6">
                            <h3 className="text-sm font-light tracking-wide text-white uppercase">
                                Services
                            </h3>
                            <ul className="space-y-3">
                                <li>
                                    <a
                                        href="#"
                                        className="text-sm font-light text-gray-400 hover:text-white transition-colors"
                                    >
                                        Acquisition
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="text-sm font-light text-gray-400 hover:text-white transition-colors"
                                    >
                                        Gestion locative
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="text-sm font-light text-gray-400 hover:text-white transition-colors"
                                    >
                                        Investissement
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="text-sm font-light text-gray-400 hover:text-white transition-colors"
                                    >
                                        Services techniques
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {/* Contact */}
                        <div className="space-y-6">
                            <h3 className="text-sm font-light tracking-wide text-white uppercase">
                                Contact
                            </h3>
                            <ul className="space-y-3">
                                <li className="text-sm font-light text-gray-400">
                                    Bab Ezzouar, Alger
                                </li>
                                <li className="text-sm font-light text-gray-400">
                                    +213 555 123 456
                                </li>
                                <li className="text-sm font-light text-gray-400">
                                    contact@mobilier-algerie.dz
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {/* Copyright */}
                <div className="py-8 border-t border-gray-800">
                    <div className="flex flex-col md:flex-row justify-between items-center">
                        <p className="text-sm font-light text-gray-400">
                            © 2024 Mobilier Algérie. Tous droits réservés.
                        </p>
                        <div className="flex space-x-8 mt-4 md:mt-0">
                            <a
                                href="#"
                                className="text-xs font-light text-gray-400 hover:text-white transition-colors uppercase tracking-wide"
                            >
                                Mentions légales
                            </a>
                            <a
                                href="#"
                                className="text-xs font-light text-gray-400 hover:text-white transition-colors uppercase tracking-wide"
                            >
                                Confidentialité
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
};

// Composant principal
const LandingPage = () => {
    useEffect(() => {
        // Smooth scroll pour les ancres
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                const target = document.querySelector(
                    link.getAttribute("href")
                );
                if (target) {
                    target.scrollIntoView({ behavior: "smooth" });
                }
            });
        });
    }, []);

    return (
        <div className="font-light">
            <Header />
            <HeroSection />
            <CollectionSection />
            <ServicesSection />
            <ContactSection />
            <Footer />
        </div>
    );
};

export default LandingPage;
