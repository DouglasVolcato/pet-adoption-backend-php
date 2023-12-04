<?php

interface GatewayInterface
{
  public function request(): Generator;
}
