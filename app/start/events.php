<?php

use Bestline\Event\Events;

Event::listen(Events::ORDER_UPDATED, 'Bestline\Event\TransactionListener@onOrderUpdated');
Event::listen(Events::ORDER_CREATED, 'Bestline\Event\TransactionListener@onOrderCreated');
Event::listen(Events::ORDER_LINEITEM_CREATED, 'Bestline\Event\TransactionListener@onOrderLineItemCreated');
Event::listen(Events::ORDER_LINEITEM_UPDATED, 'Bestline\Event\TransactionListener@onOrderLineItemUpdated');
