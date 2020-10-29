# CV_project_backend

## API
Följande API finns:
* **educationApi:**
* **infoApi:**
* **projectApi:**
* **userApi:**
* **workApi:**

Samtliga API fungerar liknande. De hämtar in filer, skapar en instans av klassen ReusableApi för att sedan anropa funktionen "setHeaders" för att ange headers. De skapar ett variabel id om det inte finns någon. Det skapar en variabel för att lagra den metoden som används i förfrågan. De skapar även en nytt databas-objekt och connectar mot databasen. Sedan skapar den även en instans av klassen som ska användas och skickar med databasen som parameter. Sedan används en switch metod där olika metoder används beroende på om förfrågan är POST, GET, PUT eller DELETE.
* **GET:** kollar om något id är medskickat och då anropas funktionen "getOne" i den klassen som angivits tidigare, annars hämtas alla. Det görs en koll på om det finns data att hämta eller ej och resultatet lagras i variabel som sedan skickas tillbaka i JSON format. 
* **POST:** tar in data och skapar php objekt. Gör en koll så att efterfrågad data finns och skickar den då till angiven klass med props och funktionen "create" anropas. Resultatet om det gick igenom eller inte lagras i en variabel och skickas sedan tillbaka med jSON format.  
* **DELETE:** gör först en koll om id är medskickat och skickar felmeddelande i resultatet annars. om det finns ett id så anropas funktionen "delete" i den angivna klassen och resultatet om det gick igenom eller ej lagras i resultatet och skickas sedan tillbaka som JSON format.
* **PUT:** gör först en koll om id är medskickat och skickar tillbaka felmeddelande om det inte är det. Om id är medskickat tar den in data och skapar php objekt. Gör en koll så att efterfrågad data finns och skickar den då till angiven klass med props och funktionen "update" anropas och ett id skickas med. Resultatet om det gick igenom eller inte lagras i en variabel och skickas sedan tillbaka med jSON format.  

Samtliga ovan returnerar ett resultat som görs om till JSON format innan det skickas tillbaka. Sedan avbryts databasanslutningen.

## Classes
Följande klasser finns i mitt REST API: 
* **Users:**
* **Works:**
* **Projects:** 
* **Educations:** 
* **Info:** 
* **Reusable API**: innehåller funktionalitet som återanvänds. Det finns en funktion som heter "getData" som anropas för att se om resultaten i databas-anropen för att hämta data innehåller data eller ej. Det finns även en funktion "setHeaders" som används för att ange headern i samtliga API. 


Samtliga klasser ovan förutom "reusableApi" fungerar liknande och innehåller följande funktioner:
* **getAll:** hämtar all data i tabellen. Om "endDate" finns så sorteras data så att senaste "endDate" visas först. Returnerar resultatet.
* **getOne:** hämtar en rad i databasen baserat på id. Returnerar resultatet.
* **create:** skapar rad i databasen. Saniterar och binder värdena innan de skickas in i databasen. Returnerar true om det gick igenom.  
* **update:** uppdaterar rad i databasen baserat på id. Saniterar och binder värdena innan de skickas in i databasen. Returnerar true om det gick igenom.  
* **delete:** raderar rad i databasen baserat på id. Returnerar resultatet. 


## Confg
* **Database:** Klass för hantering av databas. Innehåller databasinställningar samt en funktion för att connecta till databas med hjälp av PDO.
