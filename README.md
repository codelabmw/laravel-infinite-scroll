<p align="center">
    <img src="art/package-art.png" alt="Art">
    <p align="center">
        <a href="https://github.com/codelabmw/laravel-infinite-scroll/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/codelabmw/laravel-infinite-scroll/actions/workflows/run-tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/codelabmw/laravel-infinite-scroll"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/codelabmw/laravel-infinite-scroll"></a>
        <a href="https://packagist.org/packages/codelabmw/laravel-infinite-scroll"><img alt="Latest Version" src="https://img.shields.io/packagist/v/codelabmw/laravel-infinite-scroll"></a>
        <a href="https://packagist.org/packages/codelabmw/laravel-infinite-scroll"><img alt="License" src="https://img.shields.io/packagist/l/codelabmw/laravel-infinite-scroll"></a>
    </p>
</p>

# Laravel Infinite Scroll

A Laravel package for easily implementing infinite scroll in your Inertia.js applications, regardless of frontend stack (React, Vue, Svelte, etc.).

This package is designed to be stack-agnostic: each frontend stack (React, Vue, Svelte, etc.) can have its own Infinite Scroll component. The package provides a convenient mechanism to install and use the appropriate component for your stack.

---

## 📚 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
  - [Backend: InfiniteScroll Facade](#backend-infinitescroll-facade)
  - [Frontend: React Component](#frontend-react-component)
- [API Reference](#api-reference)
  - [InfiniteScroll::make](#infinitescrollmake)
  - [Console Commands](#console-commands)
  - [Stacks & Extensibility](#stacks--extensibility)
- [Troubleshooting & FAQ](#troubleshooting--faq)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [License](#license)

---

## Overview

**Laravel Infinite Scroll** brings a plug-and-play infinite scrolling experience to your Inertia.js powered Laravel apps. It seamlessly integrates backend pagination and frontend rendering, making your lists and feeds truly dynamic.

## Features

- Effortless infinite scroll for Eloquent queries, Paginators, and CursorPaginators
- Stack-agnostic: works with any Inertia frontend adapter (React, Vue, Svelte, etc.)
- Comes with ready-to-use components for common stacks (currently React; others can be contributed)
- Artisan command to install stack-specific components with stack detection and custom path support
- Fully typed, tested, and extensible

## Requirements

- PHP ^8.3
- Laravel 10+
- Inertia.js (any frontend adapter: React, Vue, Svelte, etc.)

## Installation

```bash
composer require codelabmw/laravel-infinite-scroll
```

Publish the frontend infinite scroll component for your stack:

```bash
php artisan install:infinite-scroll
```

The install command will list all supported stacks and prompt you to choose your stack and the path where to publish the component. It will also try to detect your current stack and set it as the default in the selection menu.

> **Note:** Currently, only React stack components are included. Support for other stacks (Vue, Svelte, etc.) is planned for future versions and community contributions are welcome!

## Quick Start

### Backend: Add Infinite Scroll to Your Controller

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Codelabmw\InfiniteScroll\Facades\InfiniteScroll;
use Inertia\Inertia;

final class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::query();
        // You can use a Builder, CursorPaginator, or Paginator
        return Inertia::render('articles/index', InfiniteScroll::make('articles', $articles));
    }
}
```

### Frontend: Use the Infinite Scroll Component

Depending on your stack, the component will be installed in a different location. For React, it will be installed in `resources/js/components/infinite-scroll.tsx` unless specified otherwise.

```tsx
import InfiniteScroll from "@/components/infinite-scroll";

interface Props {
  articles: { data: Article[] };
}

export default function ArticlesIndex({ articles }: Props) {
  return (
    <div>
      <InfiniteScroll data="articles">
        {articles.data.map((article) => (
          <div key={article.id}>{article.title}</div>
        ))}
      </InfiniteScroll>
    </div>
  );
}
```

---

## Usage

### Backend: InfiniteScroll Facade

The core backend API is the `InfiniteScroll` facade:

#### `InfiniteScroll::make(string $key, Builder|CursorPaginator|Paginator $data, int $perPage = 15, array $columns = ['*']): array`

- **$key**: The name of the prop (e.g. `'articles'`) as will be used in the frontend component.
- **$data**: An Eloquent Builder, CursorPaginator, or Paginator (LengthAwarePaginator|SimplePaginator) instance.
- **$perPage**: Items per page (default: 15). _Used if $data is a Builder_.
- **$columns**: Columns to select (default: all). _Used if $data is a Builder_.

**Returns:**
An array of props for Inertia, including:

- `$key` (deferred data) - The data that was passed to the facade.
- `type` (pagination type) - Either `cursor` or `paged`
- `cursor` or `page` (depending on paginator) - If cursor pagination is used, this will be the cursor to fetch the next page. If paged pagination is used, this will be the page number of the current page.
- `has_more` (bool) - Whether there are more pages to fetch.
- `per_page` (int) - The number of items per page.

**Example:**

```php
InfiniteScroll::make('articles', Article::query());
```

### Frontend: Use the Infinite Scroll Component

After publishing, import and use the `InfiniteScroll` component for your stack in your Inertia pages. The component will handle fetching more data as the user scrolls.

---

## API Reference

### InfiniteScroll::make

See [Backend Usage](#backend-infinitescroll-facade) for signature and example.

### Console Commands

#### `php artisan install:infinite-scroll`

Publishes infinite scroll components of your chosen stack to your resources directory.

### Stacks & Extensibility

- **Stack detection:** The install command tries to auto-detect your current stack (e.g., React) and sets it as the default in the selection menu.
- **Multiple stacks supported:** The package is designed to support any Inertia frontend stack. Each stack implementation is responsible for its own frontend component.
- **Adding custom stacks:** You can extend the package by implementing the `Stack` contract and registering your stack and component. Community contributions for additional stacks (Vue, Svelte, etc.) are encouraged!

---

## Troubleshooting & FAQ

- **Q: The infinite scroll component is not loading?**
  - A: Make sure you have run the install command and imported the component correctly.
  - A: Make sure you do not have a type error when specifying the data key.
- **Q: How do I customize the frontend component?**
  - A: Use component props (`whileLoading` | `whileNoMoreData`) to override default behavior.
  - A: Edit the published component in your resources directory or where you published it.
- **Q: Does this work with other stacks?**
  - A: Currently, only React components are supported out of the box. You can add other stacks by contributing to the package.

---

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines and run the test suite before submitting a PR.

### Adding Support for a New Stack

To add support for a new Inertia frontend stack (e.g., Vue, Svelte):
1. **Implement the `Stack` contract** in `src/Contracts/Stack.php` for your stack.
2. **Provide the frontend component** for your stack (e.g., Vue or Svelte InfiniteScroll component) in stubs directory.
3. **Register your stack** in the package so it appears in the install command selection menu in `src/SupportedStacks.php`.
4. **Test your integration** and update documentation/examples as needed.

Community contributions for new stacks are highly encouraged!

#### Example

```php
namespace Codelabmw\InfiniteScroll\Stacks;

use Codelabmw\InfiniteScroll\Contracts\Stack;

final class MyStack implements Stack
{
    /**
     * The display name of the stack.
     */
    public function getLabel(): string
    {
        return 'MyStack';
    }

    /**
     * The default installation path of components for this stack.
     */
    public function getDefaultInstallationPath(): string
    {
        return 'resources/js/components';
    }

    /**
     * The paths of the stubs to copy.
     *
     * @return Collection<int, string>
     */
    public function getStubs(): Collection
    {
        return collect([
            FileSystem::stubs('my-stack/infinite-scroll.ext'),
        ]);
    }

    /**
     * Whether this stack is the current one.
     */
    public function isCurrent(): bool
    {
        return $this->isTheCurrentStackInUseByApplication();
    }
}
```

Place your components in `stubs/stack/stub-name.ext`.

Then, register your stack in `src/SupportedStacks.php`:

```php
//...

class SupportedStacks
{
    /**
     * @return array<int, class-string<Stack>>
     */
    public function get(): array
    {
        return [
            //...
            MyStack::class,
        ];
    }
}
```

Now your stack will appear in the install command menu!
---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.