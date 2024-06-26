<?php

namespace Litstack\Bladesmith\Localize;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Litstack\Pages\Models\Page;
use LogicException;

class LocalizeServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('route.trans', function ($app) {
            $locales = $app['config']['app.locales'] ?: $app['config']['translatable.locales'] ?: [];
            return new TransRoute($locales);
        });

        // Macros
        $this->registerTransMacro();
        $this->registerTranslateMacro();
        $this->registerTranslatorMacro();
        $this->registerGetNameWithoutLocaleMacro();

        $this->app->afterResolving('translator', function ($app) {
            $this->app->setLocale($this->app['route.trans']->getLocale());
        });
    }

    /**
     * Register the macro.
     *
     * @return void
     */
    protected function registerTransMacro()
    {
        RouteFacade::macro('trans', function ($route, $controller) {
            return app('route.trans')->trans($route, $controller);
        });
    }

    /**
     * Register the macro.
     *
     * @return void
     */
    protected function registerTranslateMacro()
    {
        Route::macro('translate', function ($locale) {
            if (! Request::route() || Request::route()->getName() != $this->getName()) {
                throw new LogicException('You may only translate the current route.');
            }

            if (! $this->translator) {
                return url($this->uri, $this->parameters());
            }

            return call_user_func($this->translator, $locale);
        });
    }

    /**
     * Register the macro.
     *
     * @return void
     */
    protected function registerTranslatorMacro()
    {
        Route::macro('translator', function ($translator) {
            $this->translator = function ($locale) use ($translator) {
                $arguments = [
                    $locale,
                    ...array_values(Request::route()->parameters()),
                ];

                if ($translator instanceof Closure) {
                    $parameters = $translator(...$arguments);
                } else {
                    $parameters = $this->getController()->$translator(...$arguments);
                }

                $name = $this->getNameWithoutLocale();

                return __route($name, $parameters, true, $locale);
            };

            return $this;
        });

        $this->app->afterResolving('lit.pages.routes', function ($routes) {
            if (lit()->isAppTranslatable()) {
                $routes->extend(function (Route $route) {
                    $route->translator(function ($locale, $slug = null) {
                        $slug = Page::current()?->translate($locale)->t_slug;
                        if (! $slug) {
                            abort(404);
                        }

                        return ['slug' => $slug];
                    });
                });
            }
        });
    }

    /**
     * Register the macro.
     *
     * @return void
     */
    protected function registerGetNameWithoutLocaleMacro()
    {
        Route::macro('getNameWithoutLocale', function () {
            $name = $this->getName();

            foreach (config('translatable.locales') as $locale) {
                if (Str::startsWith($name, $locale.'.')) {
                    return Str::replaceFirst($locale.'.', '', $name);
                }
            }

            return $name;
        });
    }
}
