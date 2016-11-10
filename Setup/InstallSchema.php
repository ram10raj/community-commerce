<?php
/**
 * Copyright © 2015 Sundial. All rights reserved.
 */

namespace Sundial\CommunityCommerce\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'communitycommerce_charity'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('communitycommerce_charity')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'communitycommerce_charity'
        )
		->addColumn(
            'charity_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'charity_name'
        )
		->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'description'
        )
		->addColumn(
            'logo',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'logo'
        )
		->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'sort_order'
        )
		->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'status'
        )
		->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'created_at'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Sundial CommunityCommerce communitycommerce_charity'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/
		
		//Add colums to quote_table

        $quoteTable = $installer->getTable('quote');

		$columns = [
			'charity_id' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				'nullable' => true,
				'comment' => 'Charity Id',
			],
			'charity_name' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Name',
			],
			'charity_total_amount' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Total Amount',
			]

		];

		$connection = $installer->getConnection();
		foreach ($columns as $name => $definition) {
			$connection->addColumn($quoteTable, $name, $definition);
		}
		
		//add quote address table
		 $quoteAddressTable = $installer->getTable('quote_address');

		$columns = [
			
			'charity' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Amount',
			],
			'base_charity' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Base Charity Amount',
			]

		];

		$connection = $installer->getConnection();
		foreach ($columns as $name => $definition) {
			$connection->addColumn($quoteAddressTable, $name, $definition);
		}
		//add columns to order table
		$salesTable = $installer->getTable('sales_order');

		$columns = [
			'charity_id' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				'nullable' => true,
				'comment' => 'Charity Id',
			],
			'charity_name' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Name',
			],
			'charity_total_amount' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Total Amount',
			]

		];

		$connection = $installer->getConnection();
		foreach ($columns as $name => $definition) {
			$connection->addColumn($salesTable, $name, $definition);
		}
		
		//add column to sales order address table
		
		//add quote address table
		 $salesAddressTable = $installer->getTable('sales_order_address');

		$columns = [
			
			'charity' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Charity Amount',
			],
			'base_charity' => [
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' => 'Base Charity Amount',
			]

		];

		$connection = $installer->getConnection();
		foreach ($columns as $name => $definition) {
			$connection->addColumn($salesAddressTable, $name, $definition);
		}

        $installer->endSetup();

    }
}
