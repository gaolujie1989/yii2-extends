#!/usr/bin/env bash

rm -rf soap

soap-client generate:classmap --config=config/LoginServiceV20.php
soap-client generate:client --config=config/LoginServiceV20.php
soap-client generate:clientfactory --config=config/LoginServiceV20.php
soap-client generate:types --config=config/LoginServiceV20.php

soap-client generate:classmap --config=config/ParcelShopFinderServiceV50.php
soap-client generate:client --config=config/ParcelShopFinderServiceV50.php
soap-client generate:clientfactory --config=config/ParcelShopFinderServiceV50.php
soap-client generate:types --config=config/ParcelShopFinderServiceV50.php

soap-client generate:classmap --config=config/ShipmentServiceV44.php
soap-client generate:client --config=config/ShipmentServiceV44.php
soap-client generate:clientfactory --config=config/ShipmentServiceV44.php
soap-client generate:types --config=config/ShipmentServiceV44.php
