<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown inside the checkout transaction when a product's stock — re-checked
 * under a row lock — can no longer satisfy the ordered quantity. Carrying its
 * own type lets the controller surface a precise message and roll the
 * transaction back cleanly, distinct from unexpected failures.
 */
class InsufficientStockException extends RuntimeException
{
}
