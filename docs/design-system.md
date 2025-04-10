# PTT Laravel Design System

This document outlines the design and styling philosophy for our Laravel application's UI components, ensuring consistency across all pages and features.

## Core Principles

1. **Consistency** - Maintain visual and behavioral consistency across all interfaces
2. **Accessibility** - Support both light and dark modes with proper contrast ratios
3. **Responsiveness** - All components adapt gracefully to different screen sizes
4. **Simplicity** - Keep UI elements clean and focused on their primary purpose
5. **Reusability** - Build components that can be reused across the application

## Color System

### Primary Colors

- **Cyan** (`cyan-600`) - Primary action color for buttons, links, and interactive elements
- **Gray** (`gray-200/gray-700`) - Secondary/neutral action color for cancel buttons and less prominent actions
- **Red** (`red-100/red-700`) - Danger actions and important alerts

### Color Application Guidelines

- Use cyan for primary actions and focus states
- Use gray for secondary actions and UI containers
- Use red sparingly, only for destructive actions or critical alerts
- Ensure all interactive elements have visible hover and focus states

## Dark Mode Support

All components must support dark mode with these key adjustments:

- **Background Colors**: Light backgrounds become dark (white → `gray-800`)
- **Text Colors**: Dark text becomes light (black → white, `gray-700` → `gray-300`)
- **Borders**: Light borders become dark (`gray-200` → `gray-700`)
- **Form Elements**: Dark backgrounds with light text in dark mode
- **Focus States**: Maintain visibility in both modes

## Typography

- **Headings**: 
  - Page titles: `text-2xl font-bold`
  - Section headers: `text-xl font-bold`
  - Card headers: `text-lg font-medium`
- **Body Text**: `text-sm` or base size
- **Supporting Text**: `text-sm text-gray-500 dark:text-gray-400`
- **Button Text**: `text-xs font-semibold uppercase`

## Spacing System

- **Container Padding**: `px-4 sm:px-6 lg:px-8` for main containers
- **Component Padding**: `p-6` for card interiors
- **Vertical Spacing**: `mt-6` or `mb-6` between major sections
- **Grid Gap**: `gap-y-6 gap-x-4` for form grids
- **Component Spacing**: `space-x-2` or `space-y-2` for grouped elements

## Layout Patterns

### Page Structure

```html
<!-- Main container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Page title and description -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Page Title</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Page description</p>
        </div>
        <!-- Action buttons -->
        <div class="flex space-x-2">
            <!-- Buttons go here -->
        </div>
    </div>
    
    <!-- Content cards -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <!-- Card content goes here -->
        </div>
    </div>
</div>
```

### Form Layout

Forms should use a responsive grid layout:

```html
<form>
    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
        <!-- Full width field -->
        <div class="sm:col-span-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
            <input type="text" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
        </div>
        
        <!-- Half width fields -->
        <div class="sm:col-span-3">
            <!-- Form field -->
        </div>
        <div class="sm:col-span-3">
            <!-- Form field -->
        </div>
    </div>
    
    <!-- Form actions -->
    <div class="flex items-center justify-end mt-6">
        <a href="#" class="btn-secondary mr-3">Cancel</a>
        <button type="submit" class="btn-primary">Submit</button>
    </div>
</form>
```

## Component Library

### Buttons

#### Primary Button
```html
<button type="button" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
    Button Text
</button>
```

#### Secondary Button
```html
<button type="button" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
    Button Text
</button>
```

#### Danger Button
```html
<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
    Delete Item
</button>
```

#### Small Button
```html
<button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-900/30 hover:bg-cyan-200 dark:hover:bg-cyan-900/50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
    <svg class="h-4 w-4 mr-1"><!-- icon --></svg>
    Small Button
</button>
```

### Form Controls

#### Text Input
```html
<label for="field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
<input type="text" id="field" name="field" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
```

#### Select Dropdown
```html
<label for="select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
<select id="select" name="select" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600">
    <option>Option 1</option>
    <option>Option 2</option>
</select>
```

#### Textarea
```html
<label for="textarea" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
<textarea id="textarea" name="textarea" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:focus:ring-cyan-600"></textarea>
```

#### Checkbox
```html
<div class="flex items-center">
    <input type="checkbox" id="checkbox" name="checkbox" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
    <label for="checkbox" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Checkbox Label
    </label>
</div>
```

### Cards and Containers

#### Standard Card
```html
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Card content -->
    </div>
</div>
```

#### Section Card with Header
```html
<div class="mt-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Section Title</h2>
        <button type="button" class="btn-small">Action</button>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Card content -->
    </div>
</div>
```

#### Danger Zone Card
```html
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Danger Zone</h3>
            <div class="max-w-xl text-sm text-gray-500 dark:text-gray-400">
                <p>Warning message about the dangerous action.</p>
            </div>
            <div class="mt-4">
                <button type="button" class="btn-danger">Dangerous Action</button>
            </div>
        </div>
    </div>
</div>
```

## Responsive Behavior

All components should be designed with a mobile-first approach:

- **Headers**: Stack vertically on mobile, horizontal on desktop
  ```html
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  ```

- **Form Fields**: Full width on mobile, grid on tablet/desktop
  ```html
  <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
  ```

- **Buttons**: Full width on small screens (when needed)
  ```html
  <button class="w-full sm:w-auto">Button</button>
  ```

## Accessibility Considerations

- Ensure proper contrast ratios in both light and dark modes
- Use semantic HTML elements (`button` for buttons, not `div`, etc.)
- Include proper ARIA attributes when needed
- Maintain visible focus states on all interactive elements
- Provide text labels alongside icons

## Implementation Tips

1. Use Laravel Blade components to encapsulate these patterns
2. Extract common utilities into classes that can be reused
3. Follow the layout patterns consistently across all pages
4. Prefer using the defined spacing and layout systems over custom spacing
5. Test all components in both light and dark modes

## Page Templates

For specific page types, follow these patterns:

### Index Pages
- Use a header with title, description, and "Create" button
- Implement a card with a data table or grid
- Include proper pagination with consistent styling

### Create/Edit Forms
- Use a header with title, description, and navigation buttons
- Implement a form in a card container with consistent field layout
- Position form actions (cancel/submit) at the bottom right

### Detail Pages
- Use a header with title, description, and action buttons
- Organize content into logical section cards
- Include related data components (lists, tables) as needed 