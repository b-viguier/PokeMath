
SELECT *
FROM pokemon
         JOIN    (SELECT path, name FROM pokemon WHERE level = 3) AS lvl3 ON lvl3.path LIKE pokemon.path || '%'
WHERE pokemon.level=1
GROUP BY pokemon.id
ORDER BY pokemon.path
;


SELECT pokemon.*, b.id, b.name
from pokemon
         JOIN pokemon AS b ON b.id = CAST(SUBSTR(pokemon.path,2,3) AS decimal)
;


SELECT pokemon.*
FROM pokemon
ORDER BY rank, path;
