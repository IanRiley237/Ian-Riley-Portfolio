var words = [
    "AND", "BAD", "BOG", "CAT", "COP", "DIM", "EVE", "ZIP", "EAT", "RUN",
    "FUZZ", "QUIZ", "COZY", "JAPE", "JAWS", "ZOOM", "TRUE", "DUCK", "PICK",
    "FALSE", "BREAK", "BRICK", "ROUND", "TOTAL", "SCORE", "FISH", "LION", "SELF", "GOLF",
    "ANIMAL", "STUPID", "LIVING", "SOCIAL", "MARINE", "POTENT", "DIRECT", "BANANA",
    "HEALTHY", "NUCLEAR", "ITALIAN", "PRESENT", "LIMITED", "EXCITED", "ORGANIC", "DIGITAL", "OLYMPIC",
    "PHYSICAL", "STANDARD", "THANKFUL", "ORIGINAL", "GRACIOUS", "MILITARY", "COLORFUL",
    "MAXIMIZED", "CHRISTMAS", "ADVENTURE", "ALLIGATOR", "CHOCOLATE", "DANGEROUS", "HALLOWEEN", "EDUCATION",
    "TECHNOLOGY", "HELICOPTER", "LEADERSHIP", "STATISTICS", "APPETIZING", "ENDANGERED", "MICROSCOPE",
    "QUADRILLION", "FORGIVENESS", "IMAGINATION", "SERENDIPITY", "ADVERTISING", "QUINTILLION", "COMPLICATED", "AGRICULTURE", "COLONOSCOPY",
    "RELATIONSHIP", "THANKSGIVING", "APPRECIATION", "BIODIVERSITY", "CIVILIZATION", "AMBIDEXTROUS", "CHAMPIONSHIP", "INTERJECTION", "PHOTOGRAPHER",
    "ACCELEROMETER", "CONFIGURATION", "COMPREHENSIVE", "MANIFESTATION", "MISCALCULATED", "OBSERVATIONAL", "PARADOXICALLY", "SCHIZOPHRENIA",
    "CLASSIFICATION", "NEUTRALIZATION", "ANTIDEPRESSANT", "CHARACTERISTIC", "PHARMACEUTICAL", "ATTRACTIVENESS", "DECOLONIZATION", "MOUNTAINEERING", "ZOROASTRIANISM",
    "METHAMPHETATIME", "ACKNOWLEDGEMENT", "MANEUVERABILITY", "CONFIDENTIALITY", "NEUROBIOLOGICAL", "UNQUESTIONINGLY",
    "RESPONSIBILITIES", "ENVIRONMENTALIST", "UNDISCRIMINATING", "INCOMPREHENSIBLE", "COUNTERESPIONAGE",
    "TELECOMMUNICATION", "DEMATERIALIZATION", "HETEROGENEOUSNESS", "INDESTRUCTIBILITY",
    "TRANSMOGRIFICATION", "UNAPPRECIATIVENESS", "PSYCHOPATHOLOGICAL", "NEUROPSYCHOLOGICAL",
    "COUNTERINTELLIGENCE", "COUNTERPRODUCTIVELY", "CONVENTIONALISATION", "UNCOMMUNICATIVENESS",
    "COMPARTMENTALIZATION", "ANTIESTABLISHMENTISM", "INTERCOMMUNICATIONAL", "MAGNETOHYDRODYNAMICS"
];

var wordsList = new Array(words.length);
var health = 3;
var totalscore = 0;
var totalstars = 0;
var lost = false;
var won = false;
var winSound = document.getElementById("win"); 
var loseSound = document.getElementById("lose");

function Setup()
{
    lost = false;
    health = 3;
    totalscore = 0;
    totalstars = 0;

    // Set all the elements' text to their default values
    document.getElementById("button holder").innerHTML = "<button id='guessBtn' onclick='Guess()''>GUESS!</button>";
    document.getElementById("guessBtn").innerHTML = "Guess!";

    document.getElementById("scoretable").innerHTML = "<tr>" +
        "<th class='round'>Round</th>" + 
        "<th class='word'>Word</th>" + 
        "<th class='score'>Score</th>" + 
        "<th class='stars'>Stars</th>" + 
        "</tr>";
    for (var i = 1; i <= 18; i++)
    {
        var qmarks = "";
        for (var j = 1; j <= i+2; j++)
            qmarks += "?";

    document.getElementById("scoretable").innerHTML += "<tr>" +
    "<th>" + i + "</th>" +
    "<td id='round" + i + "word' class='wordValue'>" + qmarks + "</td>" +
    "<td id='round" + i + "score' class='scoreValue'>-</td>" +
    "<td id='round" + i + "stars' class='starValue'>&#9734 &#9734 &#9734</td>" +
    "</tr>";
        
    }
    document.getElementById("scoretable").innerHTML += "<tr>" +
    "<th colspan='2'>Totals:</th>" +
    "<th id='totalscore'>0</th>" +
    "<th id='totalstars'>0</th>" +
    "</tr>";

    // initialize wordsList
    for (var i = 0; i < wordsList.length; i++)
    { 
        wordsList[i] = new Array(21);
        for (var j = 0; j < wordsList[i].length; j++)
            wordsList[i][j] = 0;
    }
    
    for (var i = 0; i < words.length; i++)
    {
        var wordLength = words[i].length;
    
        var wordsOfLengthCount = ++wordsList[0][wordLength];
        wordsList[wordsOfLengthCount][wordLength] = words[i];
    }
    UpdateHealth();

    // Start game
    GetWord(3);
}

var wordToGuess;
var round;
function GetWord(length)
{
    round = length-2;
    var rand = Math.floor(Math.random() * wordsList[0][length]) + 1;
    wordToGuess = wordsList[rand][length];
    document.getElementById("word").innerHTML = Scramble(wordToGuess);
}

// Scramble all the letters of a string, then return the string
function Scramble(word)
{
    var scrambledWord = word.split("");

    while (scrambledWord.join("") == word)
        for (var i = 0; i < word.length; i++)
        {
            var Letter1 = i;
            var Letter2 = Math.floor(Math.random() * word.length);

            var temp = scrambledWord[Letter1];
            scrambledWord[Letter1] = scrambledWord[Letter2];
            scrambledWord[Letter2] = temp;
        }

    return scrambledWord.join("");
}

function Guess()
{
    if (lost)
    {
        Setup();
        return;
    }
    if(document.getElementById("input").value.toUpperCase() == wordToGuess)
    {
        var wordValue = document.getElementById("round" + round + "word");
        var scoreValue = document.getElementById("round" + round + "score");
        var starValue = document.getElementById("round" + round + "stars");

        winSound.pause();
        winSound.currentTime = 0;
        winSound.play();
        wordValue.innerHTML = wordToGuess;
        var score = Math.floor(wordToGuess.length * 10 / (4 - health));
        scoreValue.innerHTML = score;
        totalscore += score;
        document.getElementById("totalscore").innerHTML = totalscore;

        
        totalstars += health;

        document.getElementById("totalstars").innerHTML = totalstars;
        
        

        starValue.innerHTML = "";
        document.getElementById("starimgdiv").innerHTML = "";
        document.getElementById("starimgdiv").innerHTML += "<img src='star.png' id='starimg1' class='starimg s1'/>";
        document.getElementById("starimgdiv").innerHTML += "<img src='star.png' id='starimg2' class='starimg s2'/>";
        document.getElementById("starimgdiv").innerHTML += "<img src='star.png' id='starimg3' class='starimg s3'/>";
        for (var i = 1; i <= 3; i++)
        {
            document.getElementById("starimg" + i).classList.remove("inout");
            if (i <= health)
            {
                starValue.innerHTML += "&#9733";
                document.getElementById("starimg" + i).classList.add("inout");
            }
            else
                starValue.innerHTML += "&#9734";
            if (i < 3)
                starValue.innerHTML += " ";
            
        }
        wordValue.className ='in';
        scoreValue.className ='in';
        starValue.className ='in';

        health = 3;
        if (round == 18)
        {
            won = true;
            document.getElementById("button holder").innerHTML = "<p>Congratulations!</p><p>You have completed<br>the guessing game!</p>" +
                "<p>Score: " + totalscore + "</p>" +
                "<p>Stars: " + totalstars + "</p>";
        }
        else
            GetWord(++round + 2);
    }
    else
    {
        loseSound.pause();
        loseSound.currentTime = 0;
        loseSound.play();
        health--;
    }
    if (health == 0)
        lost = true;
    UpdateHealth();
}

function UpdateHealth()
{
    document.getElementById("health").innerHTML = "";
    for(var i = 1; i <= 3; i++)
    {
        document.getElementById("health").innerHTML += i <= health ? "&#x1F9E1" : "&#x1F90D";
    }
    if (health < 1)
    {
        document.getElementById("guessBtn").innerHTML = "You have lost! Start over.";
        document.getElementById("button holder").innerHTML +=
            "<p>Score: " + totalscore + "</p>" +
            "<p>Stars: " + totalstars + "</p>";
    }
}

// Sleep function
function sleep(milliseconds)
{
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++)
      if ((new Date().getTime() - start) > milliseconds)
        break;
}