<?php

namespace Bestline\Event;

class Events
{
    const ORDER_UPDATED = "bestline.order.updated";
    const ORDER_FINALIZED = "bestline.order.finalized";
    const ORDER_CREATED = "bestline.order.created";
    const ORDER_LINEITEM_CREATED = "bestline.order.lineitem.created";
    const ORDER_LINEITEM_UPDATED = "bestline.order.lineitem.updated";
}