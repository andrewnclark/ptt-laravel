# Recruitment Agency Platform Architecture Plan

## Overview

This document outlines the architecture for a recruitment agency platform with dual functionality:
1. Public-facing website for marketing, job listings, and blog content
2. Admin dashboard for CRM, ATS, job management, and content management

## Core Modules

### 1. Marketing Module
- Content management for marketing pages
- Blog posts and media management
- SEO optimization

### 2. Job Board Module
- Job listings management
- Job categories and filters
- Application submission and tracking

### 3. CRM Module
- Company/client relationship management
- Contact management
- Opportunity tracking

### 4. ATS Module (Applicant Tracking System)
- Candidate management
- Recruitment pipelines
- Interview scheduling and feedback

## Architecture Design

### Domain-Driven Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Public/
│   │   │   ├── HomeController.php
│   │   │   ├── PageController.php
│   │   │   ├── ContactController.php
│   │   │   ├── BlogController.php
│   │   │   ├── JobController.php
│   │   │   └── JobApplicationController.php
│   │   └── Admin/
│   │       ├── AdminController.php
│   │       ├── Marketing/
│   │       │   ├── PageController.php
│   │       │   └── PostController.php
│   │       ├── JobBoard/
│   │       │   ├── JobController.php
│   │       │   └── CategoryController.php
│   │       ├── Crm/
│   │       │   ├── CompanyController.php
│   │       │   ├── ContactController.php
│   │       │   └── OpportunityController.php
│   │       └── Ats/
│   │           ├── CandidateController.php
│   │           └── PipelineController.php
│   └── Middleware/
│       └── EnsureUserHasRole.php
├── Models/
│   ├── User.php
│   ├── Department.php
│   ├── Marketing/
│   │   ├── Page.php
│   │   └── Post.php
│   ├── JobBoard/
│   │   ├── Job.php
│   │   ├── Category.php
│   │   └── Application.php
│   ├── Crm/
│   │   ├── Company.php
│   │   ├── Contact.php
│   │   └── Opportunity.php
│   └── Ats/
│       ├── Candidate.php
│       ├── Pipeline.php
│       └── Stage.php
├── Services/
│   ├── Marketing/
│   │   ├── ContentService.php
│   │   └── SeoService.php
│   ├── JobBoard/
│   │   ├── JobService.php
│   │   └── ApplicationService.php
│   ├── Crm/
│   │   ├── CompanyService.php
│   │   └── OpportunityService.php
│   └── Ats/
│       ├── CandidateService.php
│       └── PipelineService.php
└── Livewire/
    ├── Public/
    │   ├── ContactForm.php
    │   ├── JobSearch.php
    │   ├── JobApplication.php
    │   └── BlogSearch.php
    └── Admin/
        ├── Marketing/
        │   ├── PageEditor.php
        │   └── PostManager.php
        ├── JobBoard/
        │   ├── JobManager.php
        │   └── ApplicationReview.php
        ├── Crm/
        │   ├── CompanyDashboard.php
        │   └── OpportunityTracker.php
        └── Ats/
            ├── CandidatePipeline.php
            └── InterviewScheduler.php
```

### Route Structure

```php
// routes/web.php - Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job:slug}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');

// routes/admin.php - Admin routes with middleware
Route::middleware(['auth', 'role:admin,recruiter'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Marketing module routes
    Route::resource('pages', Admin\PageController::class);
    Route::resource('posts', Admin\PostController::class);
    
    // Job board module routes
    Route::resource('jobs', Admin\JobController::class);
    Route::resource('categories', Admin\CategoryController::class);
    
    // CRM module routes
    Route::resource('companies', Admin\CompanyController::class);
    Route::resource('contacts', Admin\ContactController::class);
    Route::resource('opportunities', Admin\OpportunityController::class);
    
    // ATS module routes
    Route::resource('candidates', Admin\CandidateController::class);
    Route::resource('pipelines', Admin\PipelineController::class);
});
```

### View Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php       # Public layout
│   └── admin.blade.php     # Admin dashboard layout
├── public/
│   ├── home.blade.php
│   ├── about.blade.php
│   ├── contact.blade.php
│   ├── blog/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── jobs/
│       ├── index.blade.php
│       ├── show.blade.php
│       └── apply.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── marketing/
    │   ├── pages/
    │   └── posts/
    ├── jobs/
    ├── crm/
    └── ats/
```

## Database Structure

### Key Tables and Relationships

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Users          │     │  JobBoard       │     │  CRM            │
│  - id           │     │  - Jobs         │     │  - Companies    │
│  - name         │     │  - Categories   │     │  - Contacts     │
│  - email        │     │  - Applications │     │  - Opportunities│
│  - role         │     └─────────────────┘     └─────────────────┘
│  - department_id│
└─────────────────┘
       │
       │
┌──────▼──────┐     ┌─────────────────┐     ┌─────────────────┐
│ Departments │     │  Marketing      │     │  ATS            │
│ - id        │     │  - Pages        │     │  - Candidates   │
│ - name      │     │  - Posts        │     │  - Pipelines    │
└─────────────┘     │  - Media        │     │  - Stages       │
                    └─────────────────┘     └─────────────────┘
```

### Sample Migration (Jobs Table)

```php
Schema::create('jobs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->text('requirements')->nullable();
    $table->string('location');
    $table->string('employment_type');
    $table->decimal('salary_min', 10, 2)->nullable();
    $table->decimal('salary_max', 10, 2)->nullable();
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();
    $table->foreignId('company_id')->constrained();
    $table->foreignId('category_id')->constrained();
    $table->string('meta_title')->nullable(); // For SEO
    $table->text('meta_description')->nullable(); // For SEO
    $table->timestamps();
});
```

## Service Layer Pattern

Example of service implementations:

```php
// app/Services/JobBoard/JobService.php
class JobService
{
    public function getPublishedJobs(array $filters = []): Collection
    {
        // Return published jobs for public site
    }
    
    public function getAllJobs(array $filters = []): Collection
    {
        // Return all jobs for admin dashboard
    }
}

// app/Services/Crm/CompanyService.php
class CompanyService
{
    public function getActiveClients(): Collection
    {
        // Return active client companies
    }
    
    public function getLeads(): Collection
    {
        // Return prospect companies
    }
}
```

## Model Implementation Examples

```php
// app/Models/JobBoard/Job.php
class Job extends Model
{
    protected $fillable = [
        'title', 
        'slug', 
        'description', 
        'requirements',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'is_published',
        'published_at',
        'company_id',
        'category_id',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }
    
    // Helper methods
    public function getMetaTitle(): string
    {
        return $this->meta_title ?? $this->title;
    }
    
    public function getMetaDescription(): string
    {
        return $this->meta_description ?? Str::limit(strip_tags($this->description), 160);
    }
}
```

## Authentication & Authorization

Role-based access control implementation:

```php
// app/Models/User.php
class User extends Authenticatable
{
    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_RECRUITER = 'recruiter';
    public const ROLE_HIRING_MANAGER = 'hiring_manager';
    
    // User belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    // Check if user has a specific role
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    // Check if user has any of the specified roles
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
}

// app/Http/Middleware/EnsureUserHasRole.php
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! $request->user()->hasAnyRole($roles)) {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
```

## Livewire Component Example

```php
// app/Livewire/Admin/JobBoard/JobManager.php
class JobManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filters = [
        'status' => '',
        'category' => '',
    ];
    
    public function render()
    {
        $jobService = app(JobService::class);
        
        $jobs = $jobService->getAllJobs([
            'search' => $this->search,
            'status' => $this->filters['status'],
            'category' => $this->filters['category'],
        ])->paginate(10);
        
        $categories = Category::all();
        
        return view('livewire.admin.job-board.job-manager', [
            'jobs' => $jobs,
            'categories' => $categories,
        ]);
    }
    
    public function togglePublish(Job $job)
    {
        $job->update([
            'is_published' => !$job->is_published,
            'published_at' => !$job->is_published ? now() : null,
        ]);
        
        $this->dispatch('job-updated');
    }
}
```

## Development Strategy

1. **Phase 1: Foundation**
   - User authentication and authorization
   - Admin dashboard layout
   - Public website layout

2. **Phase 2: CRM Module**
   - Company management
   - Contact management
   - Opportunity tracking

3. **Phase 3: Job Board**
   - Job listings (admin + public)
   - Job categories
   - Job application system

4. **Phase 4: ATS Module**
   - Candidate management
   - Recruitment pipelines
   - Interview scheduling

5. **Phase 5: Marketing Module**
   - Page management
   - Blog system
   - SEO optimization

## Technology Stack

- **Backend**: Laravel, PHP 8.1+
- **Frontend**: Livewire, Alpine.js, Tailwind CSS
- **Database**: MySQL
- **Development Environment**: Laravel Sail (Docker)
- **Authentication**: Laravel Breeze/Fortify 