<?php
namespace Vanilo\Framework\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Category\Contracts\Taxonomy as TaxonomyContract;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Framework\Models\Address;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Framework\Factories\CheckoutDataFactory;
use Vanilo\Framework\Factories\OrderFactory;
use Vanilo\Framework\Models\Customer;
use Vanilo\Framework\Models\Order;
use Vanilo\Framework\Models\PaymentMethod;
use Vanilo\Framework\Models\Product;
use Vanilo\Framework\Models\Taxon;
use Vanilo\Framework\Models\Taxonomy;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Models\OrderProxy;
use Vanilo\Payment\Contracts\PaymentMethod as PaymentMethodContract;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutDataFactoryContract::class, CheckoutDataFactory::class);
    }

    public function boot()
    {
        parent::boot();

        // $this->app->get(ResourcePermissionMapper::class)->overrideResourcePlural('taxon', 'taxons');

        // Use the framework's extended model classes
        $registerRouteModels = config('concord.register_route_models', true);
        $this->concord->registerModel(ProductContract::class, Product::class, $registerRouteModels);
        $this->concord->registerModel(AddressContract::class, Address::class, $registerRouteModels);
        $this->concord->registerModel(CustomerContract::class, Customer::class, $registerRouteModels);
        $this->concord->registerModel(TaxonContract::class, Taxon::class, $registerRouteModels);
        $this->concord->registerModel(TaxonomyContract::class, Taxonomy::class, $registerRouteModels);
        $this->concord->registerModel(PaymentMethodContract::class, PaymentMethod::class, $registerRouteModels);
        $this->concord->registerModel(OrderContract::class, Order::class, $registerRouteModels);

        Relation::morphMap([
            app(ProductContract::class)->morphTypeName() => ProductProxy::modelClass(),
            'taxonomy'                                   => TaxonomyProxy::modelClass(),
            'taxon'                                      => TaxonProxy::modelClass(),
            'order'                                      => OrderProxy::modelClass(),
        ]);

        // Use the framework's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);

        // $this->registerIconExtensions();
        // $this->registerEnumIcons();
        // $this->loadBreadcrumbs();
        // $this->addMenuItems();
    }
}
