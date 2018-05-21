# range-finder
PHP class for finding locations within a given range from a given location in a 
mySql database.

##################
# How to use
#
This class is initialized with a location in the form (lat,lng) and a range in
miles. To use it, first call the function whereClause() to obtain a where clause
to be used in a mySql SELECT statement. The database must have columns named
"latitude" and "longitude" (or modify whereClause()). This establishes a list of
possible matches within a square (this is more efficient than examining all
possibilities). For each possibility, call the function distanceFrom() to
compare that value with the desired range.

##################
# How it works
#
As mentioned above, the first step is to establish a list of possible locations
within a square (sort of) area. Those locations are then compared with the
original location to see if they fall within the radial range. This range is
computed using the Haversine formula.

A complication is that the distance between longitudes differs between the
latitudes. Such that at latitudes 90º and -90º the distance between longitudes
is 0. This is compensated for by using 10 reference latitudes and longitudes
which correspond to the location on the globe. If this is not sufficient, the
"globe" array could be further subdivided.
