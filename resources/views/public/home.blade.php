@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="py-12">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl overflow-hidden shadow-xl mb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                    <div class="lg:w-1/2">
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight mb-6">
                            Connecting Top Talent With Premier Organizations
                        </h1>
                        <p class="text-blue-100 text-xl mb-8">
                            We help outstanding professionals find their dream jobs, and assist companies in building exceptional teams.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white focus:ring-offset-indigo-700">
                                Browse Jobs
                            </a>
                            <a href="#" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-indigo-700">
                                For Employers
                            </a>
                        </div>
                    </div>
                    <div class="lg:w-1/2">
                        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1567&h=1045&q=80" alt="People in meeting" class="rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Jobs Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Featured Job Opportunities</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Explore our latest job opportunities from top companies in various industries.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Job Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center">
                                <span class="text-gray-700 font-bold text-lg">TC</span>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Full-time</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Senior Software Engineer</h3>
                        <p class="text-gray-700 mb-4">Tech Company, Inc.</p>
                        <div class="flex items-center text-gray-500 text-sm mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            New York, NY (Remote)
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Job Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center">
                                <span class="text-gray-700 font-bold text-lg">MC</span>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Contract</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Marketing Director</h3>
                        <p class="text-gray-700 mb-4">Marketing Co.</p>
                        <div class="flex items-center text-gray-500 text-sm mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Chicago, IL
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Job Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center">
                                <span class="text-gray-700 font-bold text-lg">FS</span>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Full-time</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Financial Analyst</h3>
                        <p class="text-gray-700 mb-4">Financial Services LLC</p>
                        <div class="flex items-center text-gray-500 text-sm mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Boston, MA
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <a href="#" class="text-blue-600 font-medium hover:text-blue-800">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <a href="#" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800">
                    View All Jobs
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </section>
        
        <!-- Services Section -->
        <section class="bg-gray-50 py-16 mb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Our Recruitment Services</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        We offer a comprehensive range of recruitment solutions tailored to your specific needs.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Executive Search</h3>
                        <p class="text-gray-600">
                            Our executive search service identifies and attracts top-tier leadership talent for critical roles in your organization.
                        </p>
                    </div>
                    
                    <!-- Service 2 -->
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Professional Staffing</h3>
                        <p class="text-gray-600">
                            From entry-level to senior positions, we source and place high-quality professionals across various industries.
                        </p>
                    </div>
                    
                    <!-- Service 3 -->
                    <div class="bg-white p-8 rounded-xl shadow-md">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">HR Consulting</h3>
                        <p class="text-gray-600">
                            Our experts provide strategic HR guidance to optimize your recruitment processes and employee retention strategies.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Testimonials Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We've helped hundreds of companies and professionals find their perfect match.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Client">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Sarah Johnson</h4>
                            <p class="text-gray-600">CEO, TechStart Inc.</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "Working with this recruitment agency has been transformative for our hiring process. They truly understand our company culture and consistently provide candidates who are not only qualified but also align with our values."
                    </p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <div class="flex items-center mb-6">
                        <img class="h-12 w-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Client">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Michael Rodriguez</h4>
                            <p class="text-gray-600">CTO, CloudSystems</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "I've worked with several recruitment agencies over my career, but none have matched the level of expertise and dedication that this team provides. They found us exceptional engineering talent in record time."
                    </p>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="bg-blue-600 rounded-xl overflow-hidden shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-white mb-4">Ready to Find Your Perfect Match?</h2>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8">
                        Whether you're an employer looking for talent or a professional seeking your next opportunity, we're here to help.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="#" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white focus:ring-offset-blue-700">
                            For Job Seekers
                        </a>
                        <a href="#" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 bg-opacity-60 hover:bg-opacity-70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-blue-700">
                            For Employers
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection 