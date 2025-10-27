var OPENAI_API_KEY = "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";
var bTextToSpeechSupported = false;
var bSpeechInProgress = false;
var oSpeechRecognizer = null
var oSpeechSynthesisUtterance = null;
var oVoices = null;
var miTexto;
var destinoViajero;
var rte;

function OnLoad() {
    if ("webkitSpeechRecognition" in window) {
    } else {
        //speech to text not supported
        lblSpeak.style.display = "none";
    }

    if ('speechSynthesis' in window) {
        bTextToSpeechSupported = true;

        speechSynthesis.onvoiceschanged = function () {
            oVoices = window.speechSynthesis.getVoices();
            for (var i = 0; i < oVoices.length; i++) {
                selVoices[selVoices.length] = new Option(oVoices[i].name, i);
            }
        };
    }
}

function ChangeLang(o) {
    if (oSpeechRecognizer) {
        oSpeechRecognizer.lang = selLang.value;
        //SpeechToText()
    }
}

function Send(destino) {
//alert(destino);
    destinoViajero=destino;
    var sQuestion = "Design me a recipe of "+destino;
    if (sQuestion == "") {
        alert("Type in your question!");
        txtMsg.focus();
        return;
    }

    var oHttp = new XMLHttpRequest();
    oHttp.open("POST", "https://api.openai.com/v1/completions");
    oHttp.setRequestHeader("Accept", "application/json");
    oHttp.setRequestHeader("Content-Type", "application/json");
    oHttp.setRequestHeader("Authorization", "Bearer " + OPENAI_API_KEY)

    oHttp.onreadystatechange = function () {
        if (oHttp.readyState === 4) {
            //console.log(oHttp.status);
            var oJson = {}
            if (txtOutput.value != "") txtOutput.value += "\n";

            try {
                oJson = JSON.parse(oHttp.responseText);
            } catch (ex) {
                txtOutput.value += "Error: " + ex.message
            }

            if (oJson.error && oJson.error.message) {
                txtOutput.value += "Error: " + oJson.error.message;
            } else if (oJson.choices && oJson.choices[0].text) {
                var s = oJson.choices[0].text;

                if (selLang.value != "en-US") {
                    var a = s.split("?\n");
                    if (a.length == 2) {
                        s = a[1];
                    }
                }

                if (s == "") s = "No response";
                txtOutput.value += "Chat GPT: " + s;
                
                miTexto=s;
                document.getElementById("idText").innerText=s;
                const recipeIngredients = extractIngredients(s);
                const ingredientesCortados = detectFoodNames(s);
                // Output the extracted ingredients
                console.log('Extracted Ingredients:');
                recipeIngredients.forEach((ingredient, index) => {
                console.log(`${index + 1}. ${ingredient}`);
                });
                console.log('Extracted Ingredients cortaditos:');

                console.log('"ID","recipeName","ingredient","amount","units"');
                ingredientesCortados.forEach((ingrediente, indice) => {
                console.log(`${indice + 1}`+","+destino+','+ `${ingrediente}`+',"0",""');
                axios.post('http://localhost:8080/api/recipe', {
                    "recipeName": destino,
                    "ingredient": ingrediente,
                    "amount": "0",
                    "units": ""
                })
                  .then((response) => {
                    console.log(response);
                  }, (error) => {
                    console.log(error);
                  });
                });

                /*
               // document.getElementById("idText").innerHTML+='<h3>Get your lodgement here:<h3><p>';
               // var alojamiento='<a href="https://www.booking.com/'+destino+'">'+destino+'</a><p>';
                //document.getElementById("idText").innerHTML+=alojamiento;
                var sentencia='http://localhost:8000/location?misSitios='+s;
                axios.get(sentencia)
                  .then(function (response) {
                    //var objetoRaro= JSON.parse(response);
                    txtOutput2.value+=response['data'];
                    var lugares=response['data'];
                    let cadenaslugares=txtOutput2.value.split(',');
                    txtOutput2.value+=cadenaslugares.length;
                    document.getElementById("idText").innerHTML+='<h4>Suggested places to stay:<h4><p>';
                    for(var i=0;i<cadenaslugares.length;i++){
                        txtOutput2.value+=cadenaslugares[i];
                        var alojamiento2='<a href="https://www.booking.com/'+cadenaslugares[i]+'">'+cadenaslugares[i]+'</a> ';
                        document.getElementById("idText").innerHTML+=alojamiento2;
                    }
                    document.getElementById("idText").innerHTML+='<h4>Suggested tours and activities in:<h4><p>';
                    for(var i=0;i<cadenaslugares.length;i++){
                        txtOutput2.value+=cadenaslugares[i];
                        var actividades2='<a href="https://www.civitatis.com/'+cadenaslugares[i]+'">'+cadenaslugares[i]+'</a> ';
                        document.getElementById("idText").innerHTML+=actividades2;
                    }
                    document.getElementById("idText").innerHTML+='<h4>Tune to other travel flavours:</h4><p>';
                  //var localizaciones=JSON.stringify(response);
                  //txtOutput2.value+=JSON.stringify(response);
                  document.getElementsByTagName("h1")[0].innerHTML="OK, here is the answer:";
                  
                
                 
                  var contenido3='<a href="procesadorArt.html?destino='+destinoViajero+'">Tune to Art </a>';
                  //alert(contenido3);
                  //document.getElementById("idText").innerHTML+=rte;
                  document.getElementById("idText").innerHTML+=contenido3;
                  document.getElementById("idText").innerHTML+=' <a href="procesadorFoodie.html?destino='+destinoViajero+'">Tune to food and restaurants</a>';
                  document.getElementById("idText").innerHTML+=' <a href="procesadorSport.html?destino='+destinoViajero+'">Tune to sport</a>';
                  document.getElementById("idText").innerHTML+=' <a href="procesadorShopping.html?destino='+destinoViajero+'">Tune to shopping</a>';
                  document.getElementById("idText").innerHTML+=' <a href="procesadorNature.html?destino='+destinoViajero+'">Tune to nature</a>';
                  document.getElementById("idText").innerHTML+=' <a href="procesadorLandscape.html?destino='+destinoViajero+'">Tune to landscapes and sightseeing</a>';
                  document.getElementById("idText").innerHTML+=' <a href="procesadorHistory.html?destino='+destinoViajero+'">Tune to history</a>';
                })
                  .catch(function (error) {
                  console.log(error);
                  })
                  .then(function () {
                  });*/

                TextToSpeech(s);
            }            
        }
    };

    var sModel = selModel.value;// "text-davinci-003";
    var iMaxTokens = 2048;
    var sUserId = "1";
    var dTemperature = 0.5;    

    var data = {
        model: sModel,
        prompt: sQuestion,
        max_tokens: iMaxTokens,
        user: sUserId,
        temperature:  dTemperature,
        frequency_penalty: 0.0, //Number between -2.0 and 2.0  Positive value decrease the model's likelihood to repeat the same line verbatim.
        presence_penalty: 0.0,  //Number between -2.0 and 2.0. Positive values increase the model's likelihood to talk about new topics.
        stop: ["#", ";"] //Up to 4 sequences where the API will stop generating further tokens. The returned text will not contain the stop sequence.
    }

    oHttp.send(JSON.stringify(data));

    if (txtOutput.value != "") txtOutput.value += "\n";
    txtOutput.value += "Me: " + sQuestion;
    txtMsg.value = "";
}

function TextToSpeech(s) {
    if (bTextToSpeechSupported == false) return;
    if (chkMute.checked) return;

    oSpeechSynthesisUtterance = new SpeechSynthesisUtterance();

    if (oVoices) {
        var sVoice = selVoices.value;
        if (sVoice != "") {
            oSpeechSynthesisUtterance.voice = oVoices[parseInt(sVoice)];
        }        
    }    

    oSpeechSynthesisUtterance.onend = function () {
        //finished talking - can now listen
        if (oSpeechRecognizer && chkSpeak.checked) {
            oSpeechRecognizer.start();
        }
    }

    if (oSpeechRecognizer && chkSpeak.checked) {
        //do not listen to yourself when talking
        oSpeechRecognizer.stop();
    }

    oSpeechSynthesisUtterance.lang = selLang.value;
    oSpeechSynthesisUtterance.text = s;
    //Uncaught (in promise) Error: A listener indicated an asynchronous response by returning true, but the message channel closed 
    window.speechSynthesis.speak(oSpeechSynthesisUtterance);
}

function Mute(b) {
    if (b) {
        selVoices.style.display = "none";
    } else {
        selVoices.style.display = "";
    }
}

function SpeechToText() {

    if (oSpeechRecognizer) {

        if (chkSpeak.checked) {
            oSpeechRecognizer.start();
        } else {
            oSpeechRecognizer.stop();
        }

        return;
    }    

    oSpeechRecognizer = new webkitSpeechRecognition();
    oSpeechRecognizer.continuous = true;
    oSpeechRecognizer.interimResults = true;
    oSpeechRecognizer.lang = selLang.value;
    oSpeechRecognizer.start();

    oSpeechRecognizer.onresult = function (event) {
        var interimTranscripts = "";
        for (var i = event.resultIndex; i < event.results.length; i++) {
            var transcript = event.results[i][0].transcript;

            if (event.results[i].isFinal) {
                txtMsg.value = transcript;
                Send();
            } else {
                transcript.replace("\n", "<br>");
                interimTranscripts += transcript;
            }

            var oDiv = document.getElementById("idText");
            oDiv.innerHTML = '<span style="color: #999;">' + interimTranscripts + '</span>';
        }
    };

    oSpeechRecognizer.onerror = function (event) {

    };
}
function copiarPortapapeles(){

    try{navigator.clipboard.writeText(miTexto);}
    catch(e){alert("errorcopia");}
    alert("Travel recomendation copied!!");
}
function extractIngredients(recipeText) {
    const ingredients = [];
    const ingredientSection = recipeText.match(/Ingredients:(.*?)(?=Instructions:|$)/s);
  
    if (ingredientSection) {
      const ingredientLines = ingredientSection[1].split('\n');
      ingredientLines.forEach((line) => {
        const ingredientMatch = line.match(/- (.+)/);
        if (ingredientMatch) {
          ingredients.push(ingredientMatch[1].trim());
        }
      });
    }
  
    return ingredients;
  }
  const foodNames = [
    "pizza",
    "burger",
    "salad",
    "sushi",
    "spaghetti",
    "ice cream",
    "cake",
    "steak",
    "pasta",
    "olive oil",
    "onion",
    "garlic",
    "carrot",
    "celery",
    "ground beef",
    "white wine",
    "tomatoes",
    "can diced tomatoes",
    "sugar",
    "oregano",
    "basil",
    "salt",
    "black pepper",
    "parsley"
  ];
  
  function detectFoodNames(sentence) {
    const detectedFoods = [];
  
    // Convert the sentence to lowercase for case-insensitive matching
    const lowerCaseSentence = sentence.toLowerCase();
  
    // Check for each food name in the sentence
    for (const food of foodNames) {
      if (lowerCaseSentence.includes(food)) {
        detectedFoods.push(food);
      }
    }
  
    return detectedFoods;
  }
  
