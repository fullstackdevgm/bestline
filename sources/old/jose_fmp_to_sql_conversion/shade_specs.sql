# BASIC INFO
`id` int(11), # you know, the id
`shade_price` decimal(6,2), # price per sq. ft (nShadePrice)
`shade_name` varchar(255) NOT NULL, # name of the shade style (tShadeName)
`book` tinyint(1), # boolean if shades appears in catalog
`deco` tinyint(1), # is this a shade of type "deco", probably redundant (deco)
`poufy` tinyint(1), # is this a shade of type "poufy", probably redundant (poufy)
`pricing_type` varchar(100), # what is the model for pricing.  Possible values are: Square Feet, Linear Foot, Flat Fee
`calico_price` decimal(7,3), # a special price per sq ft given to a particular account "Calico Corners" (Calico Price) 
`lining` tinyint(1), # does this shade style take a lining? (tTakeLining)
`return` tinyint(1), # does this shade style take a return (tTakeReturn)
`valance_cost` decimal (6,2), # what is the added cost per foot? for a valance (ValancePrice)
`valance_minimum` decimal (6,2), # minimum charge for a valance added to this shade (ValanceMinimum)
`rod_type` varchar(100), # enum of materials for rods: Round Plastic, Flat Plastic, Wood (RodType)
`product_type` varchar(100), # either "shade" or "valance", (Shade_Type)
`shade_shape` varchar(100), # general category of the style, values are: Square, Square/Trapezoid, Austrian, Balloon, Dog Ear, PCloud, SCloud, TDBU/BU. (shadeShape) 
`hoist_guide_type` varchar(100) NOT NULL default 'ring', # if the style takes a ring or grommet (tRingOrGrommet)
`side_hem` decimal(6,3), # width of side hem, in inches, if any (tSideHemType)

# RINGS AND PANELS
`default_panel_spacing` decimal(6,2), # for panel calculations (DefaultPanelSpacing)
`outer_rings` decimal(6,3), # how far from the edge does the 1st ring appear (nOuterRings)
`outer_rings_divisor` decimal(6,3), # divisor to determing remaining rings spacing (nOuterRingsDivisor)
`ring_row_minimum` int(5), # minimum number of rings

# MANUFACTURING VALUES
# The following are a series of multipliers, divisors or additions made to fabric as 
# dictated by how much fabric the style consumes.  Also, those ending in "inv" exist
# because often there is an upcharge to the customer of fabric.  I.e, it may take 6
# yards of fabric to make, but they will upcharge to 8 yards due to difficulty.
`length_multiply` decimal(6,3), # (nLengthMultiply)
`length_multiply_inv` decimal(6,3), # ()
`length_plus` decimal(6,3), # ()
`length_plus_inv` decimal(6,3), # ()
`pouf_adjustment_inv` decimal(6,3), # if the shade is of style 'poufy' possible fabric size adjustment ()
`price_plus_percentage` decimal(6,3), # ()
`width_multiply` decimal(6,3), # ()
`width_multiply_inv` decimal(6,3), # ()
`width_plus` decimal(6,3), # ()
`width_plus_inv` decimal(6,3), # () 
`cut_length_add_inches` decimal(7,3), # additional buffer added to fabric cut length, if any (CutLengthAddInches)
`cut_length_add_inches_inv` decimal(7,3), # same as cut_length_add_inches but with upcharge (CutLengthAddInches_inv)
`cut_length_formula` decimal(7,3), # the formula used to calc fabric amounts (CutLengthFormula)
`height_pouf_adjustment` decimal(6,3), # adjustment to shade height for poufy shades, if any (HeightPoufAdjustment)
`height_rod_adjustment`decimal(6,3), # adjustment to rod height, if any (HeightPoufAdjustment)
`headerboard_in_deduction` decimal(6,3), # deduction taken, if any, for inside mount shades (nHeaderboardIn)
`headerboard_out_deduction` decimal(6,3), # deduction taken, if any, for outside mount shades (nHeaderboardOut)
`rod_deduction` decimal(6,3), # deduction, in inches, if any, from shade
`tdbu_clutch_deduction` decimal(6,3), # deduction, in inches taken from clutch length if the style is a tdbu (TDBU Clutch Deduction)
`tdbu_cord_lock_deduction` decimal(6,3), # deduction, in inches, taken from cord lock length if the style is a tdbu (TDBU Cord Lock Deduction)
`tdbu_motorized_deduction` decimal(6,3), # deduction, in inches, taken from motorized if the style is a tdbu (TDBU Motorized Deduction)
