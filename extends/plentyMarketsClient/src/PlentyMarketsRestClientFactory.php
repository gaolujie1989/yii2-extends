<?php

namespace lujie\plentyMarkets;

use lujie\common\account\models\Account as PlentyAccount;
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
use lujie\plentyMarkets\api\MailTemplates;
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

/**
 * This class is autogenerated by the OpenAPI gii generator
 */
class PlentyMarketsRestClientFactory extends BasePlentyMarketsRestClientFactory
{

    /**
     * @param PlentyAccount $account
     * @return Account|BasePlentyMarketsRestClient|null
     */
    public function getAccount(PlentyAccount $account): Account|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Account::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Accounting|BasePlentyMarketsRestClient|null
     */
    public function getAccounting(PlentyAccount $account): Accounting|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Accounting::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return AddressDesign|BasePlentyMarketsRestClient|null
     */
    public function getAddressDesign(PlentyAccount $account): AddressDesign|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(AddressDesign::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return AuditLog|BasePlentyMarketsRestClient|null
     */
    public function getAuditLog(PlentyAccount $account): AuditLog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(AuditLog::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Authentication|BasePlentyMarketsRestClient|null
     */
    public function getAuthentication(PlentyAccount $account): Authentication|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Authentication::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Authorization|BasePlentyMarketsRestClient|null
     */
    public function getAuthorization(PlentyAccount $account): Authorization|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Authorization::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return BI|BasePlentyMarketsRestClient|null
     */
    public function getBI(PlentyAccount $account): BI|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(BI::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Basket|BasePlentyMarketsRestClient|null
     */
    public function getBasket(PlentyAccount $account): Basket|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Basket::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Batch|BasePlentyMarketsRestClient|null
     */
    public function getBatch(PlentyAccount $account): Batch|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Batch::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Blog|BasePlentyMarketsRestClient|null
     */
    public function getBlog(PlentyAccount $account): Blog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Blog::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Board|BasePlentyMarketsRestClient|null
     */
    public function getBoard(PlentyAccount $account): Board|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Board::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Boards|BasePlentyMarketsRestClient|null
     */
    public function getBoards(PlentyAccount $account): Boards|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Boards::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Catalog|BasePlentyMarketsRestClient|null
     */
    public function getCatalog(PlentyAccount $account): Catalog|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Catalog::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Category|BasePlentyMarketsRestClient|null
     */
    public function getCategory(PlentyAccount $account): Category|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Category::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Cloud|BasePlentyMarketsRestClient|null
     */
    public function getCloud(PlentyAccount $account): Cloud|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Cloud::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Comment|BasePlentyMarketsRestClient|null
     */
    public function getComment(PlentyAccount $account): Comment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Comment::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Configuration|BasePlentyMarketsRestClient|null
     */
    public function getConfiguration(PlentyAccount $account): Configuration|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Configuration::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Document|BasePlentyMarketsRestClient|null
     */
    public function getDocument(PlentyAccount $account): Document|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Document::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return ElasticSync|BasePlentyMarketsRestClient|null
     */
    public function getElasticSync(PlentyAccount $account): ElasticSync|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ElasticSync::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Export|BasePlentyMarketsRestClient|null
     */
    public function getExport(PlentyAccount $account): Export|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Export::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return ExportSettings|BasePlentyMarketsRestClient|null
     */
    public function getExportSettings(PlentyAccount $account): ExportSettings|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ExportSettings::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Feedback|BasePlentyMarketsRestClient|null
     */
    public function getFeedback(PlentyAccount $account): Feedback|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Feedback::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Fulfillment|BasePlentyMarketsRestClient|null
     */
    public function getFulfillment(PlentyAccount $account): Fulfillment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Fulfillment::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Item|BasePlentyMarketsRestClient|null
     */
    public function getItem(PlentyAccount $account): Item|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Item::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return LegalInformation|BasePlentyMarketsRestClient|null
     */
    public function getLegalInformation(PlentyAccount $account): LegalInformation|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(LegalInformation::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Listing|BasePlentyMarketsRestClient|null
     */
    public function getListing(PlentyAccount $account): Listing|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Listing::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Log|BasePlentyMarketsRestClient|null
     */
    public function getLog(PlentyAccount $account): Log|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Log::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return MailTemplates|BasePlentyMarketsRestClient|null
     */
    public function getMailTemplates(PlentyAccount $account): MailTemplates|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(MailTemplates::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Market|BasePlentyMarketsRestClient|null
     */
    public function getMarket(PlentyAccount $account): Market|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Market::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Messenger|BasePlentyMarketsRestClient|null
     */
    public function getMessenger(PlentyAccount $account): Messenger|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Messenger::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Newsletter|BasePlentyMarketsRestClient|null
     */
    public function getNewsletter(PlentyAccount $account): Newsletter|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Newsletter::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Order|BasePlentyMarketsRestClient|null
     */
    public function getOrder(PlentyAccount $account): Order|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Order::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return OrderSummary|BasePlentyMarketsRestClient|null
     */
    public function getOrderSummary(PlentyAccount $account): OrderSummary|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(OrderSummary::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Payment|BasePlentyMarketsRestClient|null
     */
    public function getPayment(PlentyAccount $account): Payment|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Payment::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Pim|BasePlentyMarketsRestClient|null
     */
    public function getPim(PlentyAccount $account): Pim|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Pim::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return PluginMultilingualism|BasePlentyMarketsRestClient|null
     */
    public function getPluginMultilingualism(PlentyAccount $account): PluginMultilingualism|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PluginMultilingualism::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return PluginSet|BasePlentyMarketsRestClient|null
     */
    public function getPluginSet(PlentyAccount $account): PluginSet|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PluginSet::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Plugins|BasePlentyMarketsRestClient|null
     */
    public function getPlugins(PlentyAccount $account): Plugins|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Plugins::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Property|BasePlentyMarketsRestClient|null
     */
    public function getProperty(PlentyAccount $account): Property|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Property::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Returns|BasePlentyMarketsRestClient|null
     */
    public function getReturns(PlentyAccount $account): Returns|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Returns::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return ShopBuilder|BasePlentyMarketsRestClient|null
     */
    public function getShopBuilder(PlentyAccount $account): ShopBuilder|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(ShopBuilder::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Stock|BasePlentyMarketsRestClient|null
     */
    public function getStock(PlentyAccount $account): Stock|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Stock::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return StockManagement|BasePlentyMarketsRestClient|null
     */
    public function getStockManagement(PlentyAccount $account): StockManagement|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(StockManagement::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Sync|BasePlentyMarketsRestClient|null
     */
    public function getSync(PlentyAccount $account): Sync|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Sync::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Tag|BasePlentyMarketsRestClient|null
     */
    public function getTag(PlentyAccount $account): Tag|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Tag::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Ticket|BasePlentyMarketsRestClient|null
     */
    public function getTicket(PlentyAccount $account): Ticket|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Ticket::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return TicketMessage|BasePlentyMarketsRestClient|null
     */
    public function getTicketMessage(PlentyAccount $account): TicketMessage|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(TicketMessage::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Todo|BasePlentyMarketsRestClient|null
     */
    public function getTodo(PlentyAccount $account): Todo|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Todo::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return User|BasePlentyMarketsRestClient|null
     */
    public function getUser(PlentyAccount $account): User|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(User::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Warehouse|BasePlentyMarketsRestClient|null
     */
    public function getWarehouse(PlentyAccount $account): Warehouse|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Warehouse::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Webstore|BasePlentyMarketsRestClient|null
     */
    public function getWebstore(PlentyAccount $account): Webstore|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Webstore::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return Wizard|BasePlentyMarketsRestClient|null
     */
    public function getWizard(PlentyAccount $account): Wizard|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(Wizard::class, $account);
    }

    /**
     * @param PlentyAccount $account
     * @return PlentyMarketplace|BasePlentyMarketsRestClient|null
     */
    public function getPlentyMarketplace(PlentyAccount $account): PlentyMarketplace|BasePlentyMarketsRestClient|null
    {
        return $this->createClient(PlentyMarketplace::class, $account);
    }

}
