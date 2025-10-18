<?php

namespace App\Enums;

enum RelationshipToTenant :string
{
    case SPOUSE   = "Spouse";
    case CHILD    = "Child";
    case PARENT   = "Parent";
    case SIBLING  = "Sibling";
    case RELATIVE = "Relative";
    case FRIEND   = "Friend";
    case OTHER    = "Other";
}
