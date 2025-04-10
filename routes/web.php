<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Crm\CompanyManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('public.home');
})->name('home');

// Dashboard route needed for auth scaffolding
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Admin Routes (Will require authentication later)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Debug route for testing activities
    Route::get('/debug/test-activities', function() {
        // Ensure user is authenticated
        $user = \App\Models\User::first();
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Log the current auth status
        \Illuminate\Support\Facades\Log::info('Debug: Auth status', [
            'logged_in' => \Illuminate\Support\Facades\Auth::check(),
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        
        // Get or create a test company
        $company = \App\Models\Crm\Company::where('name', 'like', 'Debug Test%')->first();
        if (!$company) {
            $company = \App\Models\Crm\Company::create([
                'name' => 'Debug Test Company',
                'status' => \App\Models\Crm\Company::STATUS_LEAD,
                'is_active' => true
            ]);
            return "Created company: {$company->id}. Please reload this page to test updates.";
        }
        
        // Update the company
        $oldName = $company->name;
        $company->update([
            'name' => 'Debug Test Company ' . date('H:i:s'),
            'description' => 'Updated at ' . now()
        ]);
        
        // Manually change status to test status change activities
        $oldStatus = $company->status;
        $newStatus = $oldStatus === \App\Models\Crm\Company::STATUS_LEAD 
            ? \App\Models\Crm\Company::STATUS_PROSPECT 
            : \App\Models\Crm\Company::STATUS_LEAD;
            
        $company->changeStatus($newStatus);
        
        // Check for activities via different methods
        $activitiesViaRelation = $company->activities()->get();
        
        // Direct DB query to check activities
        $activitiesViaDirectQuery = DB::table('crm_activities')
            ->where('subject_type', \App\Models\Crm\Company::class)
            ->where('subject_id', $company->id)
            ->get();
        
        return response()->json([
            'company_id' => $company->id,
            'old_name' => $oldName,
            'new_name' => $company->name,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'activity_count_relation' => $activitiesViaRelation->count(),
            'activity_count_direct' => count($activitiesViaDirectQuery),
            'activities_relation' => $activitiesViaRelation->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'description' => $item->description,
                    'created_at' => $item->created_at,
                ];
            }),
            'activities_direct' => $activitiesViaDirectQuery,
            'observer_registered' => class_exists(\App\Observers\CompanyObserver::class),
            'auth_user' => \Illuminate\Support\Facades\Auth::id(),
            // Get a summary of the latest log entries from the storage logs
            'latest_logs' => file_exists(storage_path('logs/laravel.log')) 
                ? array_slice(file(storage_path('logs/laravel.log')), -10) 
                : []
        ]);
    })->name('debug.test-activities');
    
    // CRM Routes
    Route::prefix('crm')->name('crm.')->group(function () {
        // Companies
        Route::get('/companies', function() {
            return view('admin.crm.companies.index');
        })->name('companies.index');
        Route::get('/companies/create', function () {
            return view('admin.crm.companies.create');
        })->name('companies.create');
        Route::get('/companies/{company}', function (App\Models\Crm\Company $company) {
            return view('admin.crm.companies.show', ['company' => $company]);
        })->name('companies.show');
        Route::get('/companies/{company}/edit', function (App\Models\Crm\Company $company) {
            return view('admin.crm.companies.edit', ['company' => $company]);
        })->name('companies.edit');
        
        // Direct restore route for companies
        Route::post('/companies/restore/{id}', function ($id) {
            // Find the company including trashed
            $company = \App\Models\Crm\Company::withTrashed()->findOrFail($id);
            
            // Only try to restore if it's actually trashed
            if ($company->trashed()) {
                $company->restore();
                
                // Record activity
                $company->recordActivity(
                    'restored',
                    "Restored company: {$company->name}",
                    ['attributes' => $company->attributesToArray()]
                );
                
                // Log success
                \Illuminate\Support\Facades\Log::info('Company restored via direct route', [
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'is_last_item' => request()->has('is_last_item')
                ]);
                
                // Flash a success message
                session()->flash('success', 'Company restored successfully.');
            }
            
            // Check if this was the last trashed item
            if (request()->has('is_last_item')) {
                // Redirect to the main companies page with all companies (including restored ones)
                return redirect()->route('admin.crm.companies.index', ['trashedFilter' => 'include']);
            }
            
            // Otherwise just go back to the previous page
            return back();
        })->name('companies.restore');
        
        // Contacts
        Route::get('/contacts', [App\Http\Controllers\Admin\Crm\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/create', [App\Http\Controllers\Admin\Crm\ContactController::class, 'create'])->name('contacts.create');
        Route::get('/contacts/{id}', [App\Http\Controllers\Admin\Crm\ContactController::class, 'show'])->name('contacts.show');
        Route::get('/contacts/{company}/edit', function (App\Models\Crm\Company $company) {
            return view('admin.crm.companies.edit', ['company' => $company]);
        })->name('contacts.edit');
        
        // Opportunities
        Route::get('/opportunities', function() {
            return view('admin.crm.opportunities.index');
        })->name('opportunities.index');
        Route::get('/opportunities/create', function () {
            return view('admin.crm.opportunities.create');
        })->name('opportunities.create');
        Route::get('/opportunities/{opportunity}', function () {
            return view('admin.crm.opportunities.show');
        })->name('opportunities.show');
        Route::get('/opportunities/{opportunity}/edit', function () {
            return view('admin.crm.opportunities.edit');
        })->name('opportunities.edit');
    });
    
    // JobBoard Routes
    Route::prefix('jobs')->name('jobs.')->group(function () {
        // Jobs
        Route::get('/', function() {
            return view('admin.jobboard.jobs.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.jobboard.jobs.create');
        })->name('create');
        Route::get('/{job}', function () {
            return view('admin.jobboard.jobs.show');
        })->name('show');
        Route::get('/{job}/edit', function () {
            return view('admin.jobboard.jobs.edit');
        })->name('edit');
        
        // Categories
        Route::get('/categories', function() {
            return view('admin.jobboard.categories.index');
        })->name('categories.index');
        Route::get('/categories/create', function () {
            return view('admin.jobboard.categories.create');
        })->name('categories.create');
        
        // Applications
        Route::get('/applications', function() {
            return view('admin.jobboard.applications.index');
        })->name('applications.index');
        Route::get('/applications/{application}', function () {
            return view('admin.jobboard.applications.show');
        })->name('applications.show');
    });
    
    // ATS Routes
    Route::prefix('ats')->name('ats.')->group(function () {
        // Candidates
        Route::get('/candidates', function() {
            return view('admin.ats.candidates.index');
        })->name('candidates.index');
        Route::get('/candidates/create', function () {
            return view('admin.ats.candidates.create');
        })->name('candidates.create');
        Route::get('/candidates/{candidate}', function () {
            return view('admin.ats.candidates.show');
        })->name('candidates.show');
        Route::get('/candidates/{candidate}/edit', function () {
            return view('admin.ats.candidates.edit');
        })->name('candidates.edit');
        
        // Pipelines
        Route::get('/pipelines', function() {
            return view('admin.ats.pipelines.index');
        })->name('pipelines.index');
        Route::get('/pipelines/create', function () {
            return view('admin.ats.pipelines.create');
        })->name('pipelines.create');
        Route::get('/pipelines/{pipeline}', function () {
            return view('admin.ats.pipelines.show');
        })->name('pipelines.show');
        
        // Interviews
        Route::get('/interviews', function() {
            return view('admin.ats.interviews.index');
        })->name('interviews.index');
        Route::get('/interviews/create', function () {
            return view('admin.ats.interviews.create');
        })->name('interviews.create');
        Route::get('/interviews/{interview}', function () {
            return view('admin.ats.interviews.show');
        })->name('interviews.show');
    });
    
    // Marketing Routes
    Route::prefix('marketing')->name('marketing.')->group(function () {
        // Pages
        Route::get('/pages', function() {
            return view('admin.marketing.pages.index');
        })->name('pages.index');
        Route::get('/pages/create', function () {
            return view('admin.marketing.pages.create');
        })->name('pages.create');
        Route::get('/pages/{page}/edit', function () {
            return view('admin.marketing.pages.edit');
        })->name('pages.edit');
        
        // Posts/Blog
        Route::get('/posts', function() {
            return view('admin.marketing.posts.index');
        })->name('posts.index');
        Route::get('/posts/create', function () {
            return view('admin.marketing.posts.create');
        })->name('posts.create');
        Route::get('/posts/{post}/edit', function () {
            return view('admin.marketing.posts.edit');
        })->name('posts.edit');
        
        // Media
        Route::get('/media', function() {
            return view('admin.marketing.media.index');
        })->name('media.index');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function() {
            return view('admin.profile.index');
        })->name('index');  
        Route::get('/edit', function() {
            return view('admin.profile.edit');
        })->name('edit');
    });
});

// Auth routes
require __DIR__.'/auth.php';
