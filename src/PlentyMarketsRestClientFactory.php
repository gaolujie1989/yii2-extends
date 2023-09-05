<?php

namespace lujie\plentyMarkets;

use lujie\plentyMarkets\api\Account;
use lujie\plentyMarkets\api\Accounting;
use lujie\plentyMarkets\api\AddressDesign;
use lujie\plentyMarkets\api\AuditLog;
use lujie\plentyMarkets\api\Authentication;
use lujie\plentyMarkets\api\Authorization;
use lujie\plentyMarkets\api\BI;
use lujie\plentyMarkets\api\Basket;
use lujie\plentyMarkets\api\Batch;
use lujie\plentyMarkets\api\Blog;
use lujie\plentyMarkets\api\Board;
use lujie\plentyMarkets\api\Boards;
use lujie\plentyMarkets\api\Catalog;
use lujie\plentyMarkets\api\Category;
use lujie\plentyMarkets\api\Cloud;
use lujie\plentyMarkets\api\Comment;
use lujie\plentyMarkets\api\Configuration;
use lujie\plentyMarkets\api\CustomerContract;
use lujie\plentyMarkets\api\Document;
use lujie\plentyMarkets\api\ElasticSync;
use lujie\plentyMarkets\api\Export;
use lujie\plentyMarkets\api\ExportSettings;
use lujie\plentyMarkets\api\Feedback;
use lujie\plentyMarkets\api\Fulfillment;
use lujie\plentyMarkets\api\Item;
use lujie\plentyMarkets\api\LegalInformation;
use lujie\plentyMarkets\api\Listing;
use lujie\plentyMarkets\api\Log;
use lujie\plentyMarkets\api\Market;
use lujie\plentyMarkets\api\Messenger;
use lujie\plentyMarkets\api\Newsletter;
use lujie\plentyMarkets\api\Order;
use lujie\plentyMarkets\api\OrderSummary;
use lujie\plentyMarkets\api\Payment;
use lujie\plentyMarkets\api\Pim;
use lujie\plentyMarkets\api\PluginMultilingualism;
use lujie\plentyMarkets\api\PluginSet;
use lujie\plentyMarkets\api\Plugins;
use lujie\plentyMarkets\api\Property;
use lujie\plentyMarkets\api\Returns;
use lujie\plentyMarkets\api\ShopBuilder;
use lujie\plentyMarkets\api\Stock;
use lujie\plentyMarkets\api\StockManagement;
use lujie\plentyMarkets\api\Sync;
use lujie\plentyMarkets\api\Tag;
use lujie\plentyMarkets\api\Ticket;
use lujie\plentyMarkets\api\TicketMessage;
use lujie\plentyMarkets\api\Todo;
use lujie\plentyMarkets\api\User;
use lujie\plentyMarkets\api\Warehouse;
use lujie\plentyMarkets\api\Webstore;
use lujie\plentyMarkets\api\Wizard;
use lujie\plentyMarkets\api\PlentyMarketplace;
use yii\base\InvalidConfigException;

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class PlentyMarketsRestClientFactory extends BasePlentyMarketsRestClientFactory
{

    /**
     * @return Account|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAccount(): Account|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Account::class);
    }

    /**
     * @return Accounting|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAccounting(): Accounting|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Accounting::class);
    }

    /**
     * @return AddressDesign|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAddressDesign(): AddressDesign|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(AddressDesign::class);
    }

    /**
     * @return AuditLog|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAuditLog(): AuditLog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(AuditLog::class);
    }

    /**
     * @return Authentication|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAuthentication(): Authentication|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Authentication::class);
    }

    /**
     * @return Authorization|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getAuthorization(): Authorization|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Authorization::class);
    }

    /**
     * @return BI|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBI(): BI|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(BI::class);
    }

    /**
     * @return Basket|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBasket(): Basket|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Basket::class);
    }

    /**
     * @return Batch|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBatch(): Batch|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Batch::class);
    }

    /**
     * @return Blog|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBlog(): Blog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Blog::class);
    }

    /**
     * @return Board|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBoard(): Board|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Board::class);
    }

    /**
     * @return Boards|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getBoards(): Boards|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Boards::class);
    }

    /**
     * @return Catalog|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getCatalog(): Catalog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Catalog::class);
    }

    /**
     * @return Category|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getCategory(): Category|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Category::class);
    }

    /**
     * @return Cloud|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getCloud(): Cloud|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Cloud::class);
    }

    /**
     * @return Comment|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getComment(): Comment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Comment::class);
    }

    /**
     * @return Configuration|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getConfiguration(): Configuration|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Configuration::class);
    }

    /**
     * @return CustomerContract|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getCustomerContract(): CustomerContract|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(CustomerContract::class);
    }

    /**
     * @return Document|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getDocument(): Document|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Document::class);
    }

    /**
     * @return ElasticSync|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getElasticSync(): ElasticSync|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ElasticSync::class);
    }

    /**
     * @return Export|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getExport(): Export|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Export::class);
    }

    /**
     * @return ExportSettings|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getExportSettings(): ExportSettings|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ExportSettings::class);
    }

    /**
     * @return Feedback|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getFeedback(): Feedback|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Feedback::class);
    }

    /**
     * @return Fulfillment|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getFulfillment(): Fulfillment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Fulfillment::class);
    }

    /**
     * @return Item|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getItem(): Item|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Item::class);
    }

    /**
     * @return LegalInformation|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getLegalInformation(): LegalInformation|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(LegalInformation::class);
    }

    /**
     * @return Listing|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getListing(): Listing|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Listing::class);
    }

    /**
     * @return Log|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getLog(): Log|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Log::class);
    }

    /**
     * @return Market|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getMarket(): Market|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Market::class);
    }

    /**
     * @return Messenger|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getMessenger(): Messenger|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Messenger::class);
    }

    /**
     * @return Newsletter|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getNewsletter(): Newsletter|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Newsletter::class);
    }

    /**
     * @return Order|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getOrder(): Order|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Order::class);
    }

    /**
     * @return OrderSummary|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getOrderSummary(): OrderSummary|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(OrderSummary::class);
    }

    /**
     * @return Payment|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPayment(): Payment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Payment::class);
    }

    /**
     * @return Pim|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPim(): Pim|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Pim::class);
    }

    /**
     * @return PluginMultilingualism|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPluginMultilingualism(): PluginMultilingualism|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PluginMultilingualism::class);
    }

    /**
     * @return PluginSet|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPluginSet(): PluginSet|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PluginSet::class);
    }

    /**
     * @return Plugins|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPlugins(): Plugins|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Plugins::class);
    }

    /**
     * @return Property|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getProperty(): Property|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Property::class);
    }

    /**
     * @return Returns|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getReturns(): Returns|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Returns::class);
    }

    /**
     * @return ShopBuilder|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getShopBuilder(): ShopBuilder|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ShopBuilder::class);
    }

    /**
     * @return Stock|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getStock(): Stock|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Stock::class);
    }

    /**
     * @return StockManagement|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getStockManagement(): StockManagement|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(StockManagement::class);
    }

    /**
     * @return Sync|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getSync(): Sync|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Sync::class);
    }

    /**
     * @return Tag|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getTag(): Tag|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Tag::class);
    }

    /**
     * @return Ticket|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getTicket(): Ticket|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Ticket::class);
    }

    /**
     * @return TicketMessage|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getTicketMessage(): TicketMessage|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(TicketMessage::class);
    }

    /**
     * @return Todo|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getTodo(): Todo|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Todo::class);
    }

    /**
     * @return User|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getUser(): User|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(User::class);
    }

    /**
     * @return Warehouse|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getWarehouse(): Warehouse|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Warehouse::class);
    }

    /**
     * @return Webstore|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getWebstore(): Webstore|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Webstore::class);
    }

    /**
     * @return Wizard|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getWizard(): Wizard|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Wizard::class);
    }

    /**
     * @return PlentyMarketplace|BasePlentyMarketsRestClient|null
     * @throws InvalidConfigException
     */
    public function getPlentyMarketplace(): PlentyMarketplace|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PlentyMarketplace::class);
    }

}
