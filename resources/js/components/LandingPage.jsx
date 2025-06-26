import React, { useEffect, useState } from "react";
import styled from "styled-components";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay, EffectFade } from "swiper/modules";
import {
    HiArrowRight,
    HiHome,
    HiCog,
    HiCalendar,
    HiStar,
    HiCheckCircle,
} from "react-icons/hi";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/effect-fade";

const LandingPage = () => {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);
        };

        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    const heroSlides = [
        {
            image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
            title: "Villa Moderne Exceptionnelle",
            subtitle: "Découvrez l'élégance contemporaine",
            description: "4 chambres • 200m² • Piscine • Jardin paysager",
        },
        {
            image: "https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
            title: "Appartements Design Premium",
            subtitle: "Au cœur de la ville moderne",
            description:
                "2-4 pièces • Vue panoramique • Prestations haut de gamme",
        },
        {
            image: "https://images.unsplash.com/photo-1572120360610-d971b9d7767c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
            title: "Maisons Familiales",
            subtitle: "L'harmonie parfaite pour votre famille",
            description:
                "3-5 chambres • Espaces verts • Quartiers résidentiels",
        },
    ];

    const services = [
        {
            icon: <HiHome className="w-8 h-8" />,
            title: "Vente & Achat",
            description:
                "Accompagnement personnalisé pour tous vos projets immobiliers",
            features: [
                "Évaluation gratuite",
                "Négociation optimale",
                "Suivi complet",
            ],
        },
        {
            icon: <HiCog className="w-8 h-8" />,
            title: "Services Techniques",
            description: "Réseau d'experts pour l'entretien et la rénovation",
            features: [
                "Interventions rapides",
                "Devis gratuits",
                "Garantie qualité",
            ],
        },
        {
            icon: <HiCalendar className="w-8 h-8" />,
            title: "Gestion Locative",
            description: "Gestion complète de vos biens locatifs",
            features: [
                "Recherche locataires",
                "Suivi administratif",
                "Maintenance",
            ],
        },
    ];

    const testimonials = [
        {
            name: "Marie Dubois",
            role: "Propriétaire",
            content:
                "Service exceptionnel ! L'équipe m'a accompagnée tout au long de la vente de ma maison avec professionnalisme.",
            rating: 5,
        },
        {
            name: "Pierre Martin",
            role: "Acheteur",
            content:
                "Grâce à Mobilier, j'ai trouvé l'appartement de mes rêves. Processus fluide et conseils précieux.",
            rating: 5,
        },
        {
            name: "Sophie Bernard",
            role: "Investisseur",
            content:
                "La gestion locative est parfaite. Je recommande vivement leurs services techniques aussi.",
            rating: 5,
        },
    ];

    return (
        <div className="min-h-screen bg-white">
            {/* Navigation */}
            <nav
                className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
                    scrolled
                        ? "shadow-lg backdrop-blur-md bg-white/95"
                        : "bg-transparent"
                }`}
            >
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex items-center space-x-2">
                            <HiHome className="w-8 h-8 text-blue-600" />
                            <span className="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                                Mobilier
                            </span>
                        </div>

                        <div className="hidden items-center space-x-8 md:flex">
                            <a
                                href="#services"
                                className="font-medium text-gray-700 transition-colors hover:text-blue-600"
                            >
                                Services
                            </a>
                            <a
                                href="#properties"
                                className="font-medium text-gray-700 transition-colors hover:text-blue-600"
                            >
                                Biens
                            </a>
                            <a
                                href="#testimonials"
                                className="font-medium text-gray-700 transition-colors hover:text-blue-600"
                            >
                                Témoignages
                            </a>
                            <a
                                href="/annonces"
                                className="font-medium text-gray-700 transition-colors hover:text-blue-600"
                            >
                                Annonces
                            </a>
                        </div>

                        <div className="flex items-center space-x-4">
                            <a
                                href="/login"
                                className="font-medium text-gray-700 transition-colors hover:text-blue-600"
                            >
                                Connexion
                            </a>
                            <a
                                href="/register"
                                className="px-6 py-2 font-medium text-white bg-blue-600 rounded-full transition-colors hover:bg-blue-700"
                            >
                                S'inscrire
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section with Slides */}
            <section className="relative h-screen">
                <Swiper
                    modules={[Navigation, Pagination, Autoplay, EffectFade]}
                    effect="fade"
                    navigation
                    pagination={{ clickable: true }}
                    autoplay={{ delay: 5000, disableOnInteraction: false }}
                    loop
                    className="h-full"
                >
                    {heroSlides.map((slide, index) => (
                        <SwiperSlide key={index}>
                            <div
                                className="relative h-full bg-center bg-no-repeat bg-cover"
                                style={{
                                    backgroundImage: `url(${slide.image})`,
                                }}
                            >
                                <div className="absolute inset-0 bg-black/40"></div>
                                <div className="flex relative z-10 justify-center items-center h-full text-center text-white">
                                    <div className="px-4 mx-auto max-w-4xl">
                                        <h1 className="mb-6 text-5xl font-bold leading-tight md:text-7xl">
                                            {slide.title}
                                        </h1>
                                        <p className="mb-4 text-xl text-gray-200 md:text-2xl">
                                            {slide.subtitle}
                                        </p>
                                        <p className="mb-8 text-lg text-gray-300">
                                            {slide.description}
                                        </p>
                                        <div className="flex flex-col gap-4 justify-center sm:flex-row">
                                            <a
                                                href="/annonces"
                                                className="inline-flex justify-center items-center px-8 py-4 text-lg font-semibold text-white bg-blue-600 rounded-full transition-all duration-300 hover:bg-blue-700 group"
                                            >
                                                Découvrir nos biens
                                                <HiArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
                                            </a>
                                            <a
                                                href="#services"
                                                className="px-8 py-4 text-lg font-semibold text-white rounded-full border-2 border-white transition-all duration-300 hover:bg-white hover:text-gray-900"
                                            >
                                                Nos services
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </SwiperSlide>
                    ))}
                </Swiper>
            </section>

            {/* Services Section */}
            <section id="services" className="py-20 bg-gray-50">
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-16 text-center">
                        <h2 className="mb-4 text-4xl font-bold text-gray-900 md:text-5xl">
                            Nos{" "}
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                                Services
                            </span>
                        </h2>
                        <p className="mx-auto max-w-3xl text-xl text-gray-600">
                            Une gamme complète de services immobiliers pour
                            répondre à tous vos besoins avec excellence et
                            professionnalisme
                        </p>
                    </div>

                    <div className="grid gap-8 md:grid-cols-3">
                        {services.map((service, index) => (
                            <div
                                key={index}
                                className="p-8 bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl group"
                            >
                                <div className="mb-6 text-blue-600 transition-transform duration-300 group-hover:scale-110">
                                    {service.icon}
                                </div>
                                <h3 className="mb-4 text-2xl font-bold text-gray-900">
                                    {service.title}
                                </h3>
                                <p className="mb-6 text-gray-600">
                                    {service.description}
                                </p>
                                <ul className="space-y-2">
                                    {service.features.map((feature, idx) => (
                                        <li
                                            key={idx}
                                            className="flex items-center text-gray-700"
                                        >
                                            <HiCheckCircle className="mr-2 w-5 h-5 text-green-500" />
                                            {feature}
                                        </li>
                                    ))}
                                </ul>
                                <a
                                    href="/dashboard"
                                    className="inline-flex items-center mt-6 font-semibold text-blue-600 transition-colors group-hover:text-blue-700"
                                >
                                    En savoir plus
                                    <HiArrowRight className="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" />
                                </a>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Properties Preview Section */}
            <section id="properties" className="py-20">
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-16 text-center">
                        <h2 className="mb-4 text-4xl font-bold text-gray-900 md:text-5xl">
                            Biens{" "}
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                                Exclusifs
                            </span>
                        </h2>
                        <p className="mx-auto max-w-3xl text-xl text-gray-600">
                            Découvrez notre sélection de propriétés
                            exceptionnelles
                        </p>
                    </div>

                    <Swiper
                        modules={[Navigation, Pagination]}
                        spaceBetween={30}
                        slidesPerView={1}
                        navigation
                        pagination={{ clickable: true }}
                        breakpoints={{
                            640: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 },
                        }}
                        className="pb-12"
                    >
                        {[
                            {
                                image: "https://images.unsplash.com/photo-1605146769289-440113cc3d00?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                                title: "Villa Contemporaine",
                                location: "Neuilly-sur-Seine",
                                price: "2,850,000€",
                                details: "5 pièces • 250m² • Jardin",
                            },
                            {
                                image: "https://images.unsplash.com/photo-1568605114967-8130f3a36994?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                                title: "Penthouse Moderne",
                                location: "Paris 16ème",
                                price: "1,950,000€",
                                details: "4 pièces • 180m² • Terrasse",
                            },
                            {
                                image: "https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                                title: "Loft Industriel",
                                location: "Lyon 2ème",
                                price: "875,000€",
                                details: "3 pièces • 120m² • Balcon",
                            },
                            {
                                image: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                                title: "Maison Familiale",
                                location: "Versailles",
                                price: "1,250,000€",
                                details: "6 pièces • 200m² • Piscine",
                            },
                        ].map((property, index) => (
                            <SwiperSlide key={index}>
                                <div className="overflow-hidden bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl group">
                                    <div className="overflow-hidden relative">
                                        <img
                                            src={property.image}
                                            alt={property.title}
                                            className="object-cover w-full h-64 transition-transform duration-500 group-hover:scale-105"
                                        />
                                        <div className="absolute top-4 right-4 px-3 py-1 text-sm font-semibold text-white bg-blue-600 rounded-full">
                                            {property.price}
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <h3 className="mb-2 text-xl font-bold text-gray-900">
                                            {property.title}
                                        </h3>
                                        <p className="mb-4 text-gray-600">
                                            {property.location}
                                        </p>
                                        <p className="mb-4 text-sm text-gray-500">
                                            {property.details}
                                        </p>
                                        <a
                                            href="/annonces"
                                            className="inline-flex items-center font-semibold text-blue-600 transition-colors hover:text-blue-700"
                                        >
                                            Voir le bien
                                            <HiArrowRight className="ml-2 w-4 h-4" />
                                        </a>
                                    </div>
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>

                    <div className="mt-12 text-center">
                        <a
                            href="/annonces"
                            className="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-blue-600 rounded-full transition-colors hover:bg-blue-700 group"
                        >
                            Voir toutes les annonces
                            <HiArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
                        </a>
                    </div>
                </div>
            </section>

            {/* Testimonials Section */}
            <section id="testimonials" className="py-20 bg-gray-50">
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-16 text-center">
                        <h2 className="mb-4 text-4xl font-bold text-gray-900 md:text-5xl">
                            Nos{" "}
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                                Clients
                            </span>
                        </h2>
                        <p className="text-xl text-gray-600">
                            Témoignages de satisfaction
                        </p>
                    </div>

                    <Swiper
                        modules={[Navigation, Pagination, Autoplay]}
                        spaceBetween={30}
                        slidesPerView={1}
                        navigation
                        pagination={{ clickable: true }}
                        autoplay={{ delay: 4000 }}
                        breakpoints={{
                            768: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 },
                        }}
                        className="pb-12"
                    >
                        {testimonials.map((testimonial, index) => (
                            <SwiperSlide key={index}>
                                <div className="p-8 bg-white rounded-2xl shadow-lg">
                                    <div className="flex mb-4">
                                        {[...Array(testimonial.rating)].map(
                                            (_, i) => (
                                                <HiStar
                                                    key={i}
                                                    className="w-5 h-5 text-yellow-400"
                                                />
                                            )
                                        )}
                                    </div>
                                    <p className="mb-6 italic text-gray-700">
                                        "{testimonial.content}"
                                    </p>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">
                                            {testimonial.name}
                                        </h4>
                                        <p className="text-sm text-blue-600">
                                            {testimonial.role}
                                        </p>
                                    </div>
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
                <div className="px-4 mx-auto max-w-4xl text-center sm:px-6 lg:px-8">
                    <h2 className="mb-6 text-4xl font-bold text-white md:text-5xl">
                        Prêt à concrétiser votre projet ?
                    </h2>
                    <p className="mb-8 text-xl text-blue-100">
                        Contactez nos experts dès aujourd'hui pour un
                        accompagnement personnalisé
                    </p>
                    <div className="flex flex-col gap-4 justify-center sm:flex-row">
                        <a
                            href="/register"
                            className="inline-flex justify-center items-center px-8 py-4 text-lg font-semibold text-blue-600 bg-white rounded-full transition-colors hover:bg-gray-100 group"
                        >
                            Commencer maintenant
                            <HiArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
                        </a>
                        <a
                            href="/annonces"
                            className="px-8 py-4 text-lg font-semibold text-white rounded-full border-2 border-white transition-all duration-300 hover:bg-white hover:text-blue-600"
                        >
                            Explorer les biens
                        </a>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="py-16 text-white bg-gray-900">
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="grid gap-8 md:grid-cols-4">
                        <div>
                            <div className="flex items-center mb-4 space-x-2">
                                <HiHome className="w-8 h-8 text-blue-500" />
                                <span className="text-2xl font-bold">
                                    Mobilier
                                </span>
                            </div>
                            <p className="mb-4 text-gray-400">
                                Votre partenaire de confiance pour tous vos
                                projets immobiliers
                            </p>
                        </div>

                        <div>
                            <h3 className="mb-4 font-semibold">Services</h3>
                            <ul className="space-y-2 text-gray-400">
                                <li>
                                    <a
                                        href="#"
                                        className="transition-colors hover:text-white"
                                    >
                                        Vente & Achat
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="transition-colors hover:text-white"
                                    >
                                        Services Techniques
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="transition-colors hover:text-white"
                                    >
                                        Gestion Locative
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 className="mb-4 font-semibold">Navigation</h3>
                            <ul className="space-y-2 text-gray-400">
                                <li>
                                    <a
                                        href="/annonces"
                                        className="transition-colors hover:text-white"
                                    >
                                        Annonces
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="/login"
                                        className="transition-colors hover:text-white"
                                    >
                                        Connexion
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="/register"
                                        className="transition-colors hover:text-white"
                                    >
                                        S'inscrire
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 className="mb-4 font-semibold">Contact</h3>
                            <ul className="space-y-2 text-gray-400">
                                <li>01 23 45 67 89</li>
                                <li>contact@mobilier.fr</li>
                                <li>
                                    123 Avenue de l'Immobilier
                                    <br />
                                    75001 Paris
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="pt-8 mt-12 text-center text-gray-400 border-t border-gray-800">
                        <p>&copy; 2024 Mobilier. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
};

export default LandingPage;
