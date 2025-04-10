<?php

declare(strict_types=1);

namespace App\Livewire\Admin\JobBoard;

use App\Models\JobBoard\JobCategory;
use App\Services\JobBoard\JobService;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    /**
     * Search term.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Active filter.
     *
     * @var bool|null
     */
    public ?bool $isActive = null;

    /**
     * Sort field.
     *
     * @var string
     */
    public string $sortField = 'sort_order';

    /**
     * Sort direction.
     *
     * @var string
     */
    public string $sortDirection = 'asc';

    /**
     * Category being deleted.
     *
     * @var JobCategory|null
     */
    public ?JobCategory $deletingCategory = null;

    /**
     * Category being edited or created.
     *
     * @var array
     */
    public array $categoryForm = [
        'id' => null,
        'name' => '',
        'description' => '',
        'color' => '#3b82f6',
        'icon' => 'briefcase',
        'is_active' => true,
        'sort_order' => 0,
    ];

    /**
     * Form mode.
     *
     * @var string
     */
    public string $formMode = 'create';

    /**
     * Show form modal.
     *
     * @var bool
     */
    public bool $showFormModal = false;

    /**
     * Reset pagination when filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status changes.
     */
    public function updatedIsActive(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by the given field.
     *
     * @param string $field
     * @return void
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Set the category to be deleted.
     *
     * @param JobCategory $category
     * @return void
     */
    public function confirmCategoryDeletion(JobCategory $category): void
    {
        $this->deletingCategory = $category;
    }

    /**
     * Delete the category.
     *
     * @return void
     */
    public function deleteCategory(): void
    {
        if ($this->deletingCategory) {
            $this->deletingCategory->delete();
            $this->deletingCategory = null;
            session()->flash('success', 'Category deleted successfully.');
        }
    }

    /**
     * Cancel category deletion.
     *
     * @return void
     */
    public function cancelDeletion(): void
    {
        $this->deletingCategory = null;
    }

    /**
     * Toggle category active status.
     *
     * @param JobCategory $category
     * @return void
     */
    public function toggleActive(JobCategory $category): void
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        session()->flash('success', $category->is_active 
            ? 'Category activated successfully.'
            : 'Category deactivated successfully.');
    }

    /**
     * Create a new category.
     *
     * @return void
     */
    public function createCategory(): void
    {
        $this->resetValidation();
        $this->formMode = 'create';
        $this->categoryForm = [
            'id' => null,
            'name' => '',
            'description' => '',
            'color' => '#3b82f6',
            'icon' => 'briefcase',
            'is_active' => true,
            'sort_order' => JobCategory::max('sort_order') + 10,
        ];
        $this->showFormModal = true;
    }

    /**
     * Edit a category.
     *
     * @param JobCategory $category
     * @return void
     */
    public function editCategory(JobCategory $category): void
    {
        $this->resetValidation();
        $this->formMode = 'edit';
        $this->categoryForm = [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description ?? '',
            'color' => $category->color ?? '#3b82f6',
            'icon' => $category->icon ?? 'briefcase',
            'is_active' => $category->is_active,
            'sort_order' => $category->sort_order,
        ];
        $this->showFormModal = true;
    }

    /**
     * Save the category.
     *
     * @return void
     */
    public function saveCategory(): void
    {
        $this->validate([
            'categoryForm.name' => 'required|string|max:255',
            'categoryForm.description' => 'nullable|string|max:1000',
            'categoryForm.color' => 'required|string|max:50',
            'categoryForm.icon' => 'required|string|max:50',
            'categoryForm.is_active' => 'boolean',
            'categoryForm.sort_order' => 'integer|min:0',
        ]);

        $data = [
            'name' => $this->categoryForm['name'],
            'slug' => Str::slug($this->categoryForm['name']),
            'description' => $this->categoryForm['description'],
            'color' => $this->categoryForm['color'],
            'icon' => $this->categoryForm['icon'],
            'is_active' => $this->categoryForm['is_active'],
            'sort_order' => $this->categoryForm['sort_order'],
        ];

        if ($this->formMode === 'edit' && $this->categoryForm['id']) {
            $category = JobCategory::find($this->categoryForm['id']);
            if ($category) {
                $category->update($data);
                session()->flash('success', 'Category updated successfully.');
            }
        } else {
            JobCategory::create($data);
            session()->flash('success', 'Category created successfully.');
        }

        $this->showFormModal = false;
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        $query = JobCategory::query();

        // Apply search filter
        if (!empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
        }

        // Apply active filter
        if ($this->isActive !== null) {
            $query->where('is_active', $this->isActive);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $categories = $query->withCount('jobs')->paginate(10);

        return view('livewire.admin.jobboard.category-manager', [
            'categories' => $categories,
        ]);
    }
} 