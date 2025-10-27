/*Nivel uno: ciudades importantes. Nivel dos: regiones interesantes y ciudades secundarias. Nivel tres:localidades turísticas. Nivel 4: barrios curiosos de grandes ciudades, entornos naturales, playas */
const patternSetNivelUno = ["Barcelona", "Madrid", "Valencia", "Seville", "Mallorca", "Bangkok", "Jakarta", "Krabi", "Labuan", "Lombok", "Yogyakarta","Surabaya","Gili","Phi Phi","Nusa","Phuket", "Chiang Mai", "Chiang Rai","Krabi","Koh Samui","Koh Tao","Khao Lak", "Paris", "Bordeaux", "Nice","Strasbourg","Marseille", "Oporto", "Portimao", "Lisboa", "Albufeira", "Peniche","Funchal","Sintra","Lagos","Ponta Delgada","Estoril","Cascais","Nazaré","Coímbra", "Naples","Florence","Rome","Milan","Sorrento","Venice","Civitavecchia","Palermo", "Pompeii", "Bergamo","Bari", "London","Edinburgh","Dubai", "Interlaken","Grindelwald","Zurich","Bern","Montreux","Geneva","Lucerne","Zermatt","Lauterbrunnen", "Manama","Doha", "Hawar","Jodhpur", "Chennai","Delhi","Jaipur","Agra","Varanasi","Munnar","Chennai","Udaipur", "Al Ula","Riyadh","Jeddah","Medina","New York","Las Vegas","San Francisco","Miami","Los Angeles","Orlando","Washington","Page","Grand Canyon","Seoul","Busan","Jeju","Andong","Daegu","Vienna","Salzburg","Inssbruck","Klosterneuburg","Neustift im Stubaital","Eisenstadt","Cuzco","Lima","Puno","Arequipa","Nazca","Paracas","Ica","Aguas Calientes","El Nido","Manila","Bohol","Coron","Puerto Princesa","Cebu","Boracay","Port Barton","Siargao","Jerusalem","Tel Aviv","Asdod","Haifa","Nazareth","Aruba","Dublin","Galway","Cork","Killarney","Cobh","Rossaveel","Kilkenny","Aillemore","Letterfrack","Nassau","Tokyo","Kyoto","Osaka","Hiroshima","Nara","Miyajima","Shirakawa","Takayama","Kanazawa","Arenal","San José","Manuel Antonio","Monteverde","Guanacaste","Bahia Drake","La Fortuna","Sierpe","Rincón de la Vieja","Toronto","Vancouver","Montreal","Quebec","Victoria","Whitehorse","Ottawa","Niagara Falls","Tadoussac", "Medellín","Cartagena de Indias","Bogotá", "Cali","Santa Marta","Pereira","Leticia","Salato","San Andrés","Cologne","Nuremberg","Frankfurt","Kiel","Potsdam","Dresden","Munich","Berlin","Hamburg","Marrakech","Fez","Agadir","Tangier","Merzouga","Casablanca","Essaouira","Chefchaouen","Ouarzazate","Belfast","Liverpool","Inverness","Glasgow","Oxford","Manchester","Gibraltar","Cairns","Sydney","Yulara","Gold Coast","Airlie Beach", "Melbourne", "Perth","Darwin", "Hobart","Athens","Santorini","Milos","Mykonos","Corfu","Heraklion","Rhodes","Kalambaka","Zante","Amsterdam","Rotterdam","Oranjestad","Utrecht","Willemstad","Volendam","Delft","Marken","Krakow","Warsaw","Wroclaw","Gdansk","Oswiecim","Katowice","Poznan","Torun","Szczecin","Prague","Cesky Krumlov","Punta Cana","Samana"];
const patternSetNivelDos = ["Cantabria", "Asturias", "Euskadi","Basque Country", "Navarra", "Navarre", "Pamplona", "Bilbao","Zaragoza","Saragossa","Jaca", "Ibiza","Canarias", "Canary islands", "Costa Brava", "Costa de la Luz", "Costa Daurada","Costa Blanca", "Costa del Sol", "San Sebastián", "San Sebastian", "Tarragona","Lanzarote", "Alicante", "Menorca", "Bali", "Fuerteventura", "Malaga", "Málaga", "Ronda","Granada","Bayeux", "Loire", "Biarritz", "Saint Jean de Luz","La Romana","Samaná","Juan Dolio","Sabana de la Mar","Jarabacoa","Boca Chica","Puerto Plata","Santo Domingo","Mexico City","Cancun","Oaxaca","Merida","Tulum","Puerto Morelos","Cozumel","Rovaniemi","Helsinki","Stockholm","Malmö","Copenhagen","Sofia","Plovdiv","Bucharest","Brasov","Bran","Reykjavik","Skaftafell","Husavik","Dubrovnik","Split","Zagreb","Zadar","El Calafate","Buenos Aires","Ushuaia","Bariloche","Puerto Iguazu","Santiago de Chile","Atacama","Puerto Natales","Punta Arenas", "Easter Island","Stavanger","Oslo","Bergen","Chichen Itza","Cairo","Aswan","Luxor","Sharm el Sheikh","Hurghada","Luxor","Giza"];
const patternSetNivelTres=["Laredo","Zarautz", "Orio", "Hondarribia","Llanes", "Zumaia"];
const patternSetNivelCuatro=["Gothic", "Chueca", "Malasaña", "Zarautz","Riglos","Ordesa", "Urdaibai","Gulpiyuri","Caló des Moro", "Sa Calobra", "Es Trenc","Sa Mitjana", "Aiguablava", "Sa Tuna", "Caló des Marmols", "Formentor", "Güell", "Guell", "Urederra", "Graciosa","Burj Khalifa"];

var idOrigenClick="";
var respuestaFinal="";


function makeStreamApiCall(paisDestino) {
	isStreaming = true;
	var destinoViajero=paisDestino;

	const API_KEY = "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";
	const API_URL = "https://api.openai.com/v1/chat/completions";
	const xhr = new XMLHttpRequest();
	xhr.open("POST", API_URL, true);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.setRequestHeader("Authorization", `Bearer ${API_KEY}`);
	xhr.setRequestHeader('Accept', 'text/event-stream');
	const userstring = "Design me a recipe of "+paisDestino;
	if (!userstring.trim()) {
	return;
	}

	const reqBody = {
		messages: [
			...buildContextString(),
			{ "role": "user", "content": userstring }],
		model: "gpt-3.5-turbo",
		max_tokens: 750,
		temperature: 1,
		top_p: 1,
		stream: true,
	};
	const parentresponsContainer = document.getElementById('idText');
	/*const userContainer = createResponse('user', userstring);
	parentresponsContainer.appendChild(userContainer);
	parentresponsContainer.scrollTop = userContainer.offsetTop - parentresponsContainer.offsetTop;*/

	const responseContainer = createResponse('assistant', 'Designing your recipe...');
	parentresponsContainer.appendChild(responseContainer);
	parentresponsContainer.scrollTop = responseContainer.offsetTop - parentresponsContainer.offsetTop;

	xhr.onreadystatechange = function () {
		if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
			isStreaming = false;
            const lastChild = responseContainer.lastChild;
           /* lastChild.innerHTML="";
            const keyword="Day";
            //console.log("hepasadoporaqui");
            const arrayOfStrings = generateArrayOfStrings(respuestaFinal, keyword);
            for(let i=1; i<arrayOfStrings.length; i++){
                //console.log(arrayOfStrings[i]+'\n');
                lastChild.innerHTML+='<h2> Day '+i+':</h2>';
                lastChild.innerHTML+='<p>'+arrayOfStrings[i]+'</p>';
            
            //y aquí viene la imagen correspondiente al link
            //detectar el pattern si es de nivel 1,2,3 o 4
            //comenzamos con nivel 4
            var miActividad4= detectPattern(arrayOfStrings[i],patternSetNivelCuatro);

            if(!(miActividad4 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad4);
               lastChild.innerHTML+='<img src="./img/'+miActividad4+'.jpg" style="width: 100%; margin-top: -20px;margin-bottom: 20px;height: auto;max-height: 400px;border-radius: 10px;object-fit: cover;object-position: center;""></a>';
               lastChild.innerHTML+='<a href="'+devolverDirReal(miActividad4, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad4+'</a>';
            }
            //vamos a por la 3
            var miActividad3= detectPattern(arrayOfStrings[i],patternSetNivelTres);

            if(!(miActividad3 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad3);
               lastChild.innerHTML+='<img src="./img/'+miActividad3+'.jpg">';
               lastChild.innerHTML+='<a href="'+devolverDirReal(miActividad3, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad3+'</a>';
            }
            //ahora a por la dos
            var miActividad2= detectPattern(arrayOfStrings[i],patternSetNivelDos);

            if(!(miActividad2 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad2);
               lastChild.innerHTML+='<img src="./img/'+miActividad2+'.jpg">';
               lastChild.innerHTML+='<a href="'+devolverDirReal(miActividad2, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad2+'</a>';

            }
            //y finalmente a por la uno
            var miActividad1= detectPattern(arrayOfStrings[i],patternSetNivelUno);

            if(!(miActividad1 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad1);
               lastChild.innerHTML+='<img src="././img/'+miActividad1+'.jpg">';
               lastChild.innerHTML+='<a href="'+devolverDirReal(miActividad1, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad1+'</a>';
            }
        }*/
			//showStatus("Stream request completed successfully.");

		}

	};

	xhr.onprogress = function (event) {
		if (xhr.status === 401 || xhr.status === 429) {
			const errorinfo = JSON.parse(xhr.responseText);
			console.log(errorinfo);
			const lastChild = responseContainer.lastChild;
			lastChild.innerHTML = errorinfo.error.message;
			//showStatus(xhr.status);
			isStreaming = false;
			return;
		}

		const responseText = xhr.responseText.trim();
		if (responseText.length > 0) {
			let buffer = '';
			let responseJson;
			let lines = responseText.split('\n');
			for (let i = 0; i < lines.length; i++) {
				let line = lines[i].trim();
				if (line.startsWith('data:')) {
					line = line.substring(5).trim();
					if (line != '[DONE]') {
						responseJson = JSON.parse(line);
						if (responseJson.choices[0].delta.content){
							buffer += responseJson.choices[0].delta.content;
						   }
					} 
					else{
					console.log(buffer+"\n");
				    }
				};
			};
			//console.log(buffer+"\n");
			const lastChild = responseContainer.lastChild;
            mibuf=processSpecialChar(buffer);
			mibuf=buffer;
			//mibuf=mibuf.replace('&lt;', '/</').replace('&gt;', '/>/');
//lastChild.innerHTML=buffer;

			document.getElementById("idText").innerHTML =htmlEncode(mibuf);
            //respuestaFinal+=mibuf;
			//console.log(parentresponsContainer.scrollTop ,responseContainer.offsetTop , parentresponsContainer.offsetTop);
			parentresponsContainer.scrollTop = responseContainer.offsetTop - parentresponsContainer.offsetTop;
            document.getElementsByClassName("titulo")[0].innerHTML="OK, here is your recipe for "+paisDestino;
            document.getElementsByClassName("animacioncarga")[0].style.display="none";
		     }
          
            else {
			isStreaming = false;
            
			//showStatus("Stream request completed.");
		}
	};

	xhr.send(JSON.stringify(reqBody));
}

function processSpecialChar(text) {
	return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}	


function createResponse(role, responsetext) {

	const context = processSpecialChar(responsetext);
	

	//const autoselected=document.getElementById("autoSelect").checked;


	// create the container div
	const container = document.createElement('div');
	//container.classList.add('LocalGPTrespons-container');

	// create the role img
/*	const roleImg = document.createElement('img');
	roleImg.classList.add('LocalGPTrole');
	roleImg.src = role + '.png';
	roleImg.title = role;
	roleImg.addEventListener('click', function () {
		// rotate the roles "user", "assistant", "system"
		const roles = ["user", "assistant", "system"];
		const currentRole = roles.indexOf(roleImg.title);
		const nextRole = (currentRole + 1) % roles.length;
		roleImg.title = roles[nextRole];
		roleImg.src = roles[nextRole] + '.png';
	});*/

	// create the context div
	const contextDiv = document.createElement('div');
	contextDiv.classList.add('LocalGPTcontext');
	contextDiv.innerHTML = context;
	//if(autoselected)
	//	contextDiv.style.backgroundColor = 'yellow';

	// append the elements to the container
	//container.appendChild(roleImg);
	container.appendChild(contextDiv);
	//contextDiv.addEventListener('click', function (event) {
		//console.log(this, event.target.parentElement);
		// Check if the div is already selected
	//	if (this.style.backgroundColor === 'yellow') {
			// Switch back to original color
	//		this.style.backgroundColor = '';
	//	} else {
	//		// Set the background color of the clicked div
	//		this.style.backgroundColor = 'yellow';
	//	}
	//});

	// return the container element
	return container;
}



function exportContextToFile() {
	const contexts = buildContextString();
	if (contexts.length <= 0) {
		alert('Please select the context you want to save.');
		return;
	}

	const file = new Blob([JSON.stringify(contexts)], { type: 'application/json' });
	const url = URL.createObjectURL(file);

	const link = document.createElement('a');
	link.href = url;
	link.download = 'chat_history_data.json';
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function importContextFromFile() {
	const fileInput = document.createElement("input");
	fileInput.type = "file";
	fileInput.accept = ".json";
	fileInput.addEventListener('change', event => {
		const file = event.target.files[0];
		const reader = new FileReader();
		reader.addEventListener('load', event => {
			const newContexts = JSON.parse(reader.result);
			//console.log(newContexts);
			//load the context and append them at the end of respons area
			newContexts.forEach((context) => {
				const newchat = createResponse(context.role, context.content);
				document.getElementById('response').appendChild(newchat);
			});
		});
		reader.readAsText(file);
	});


	// Click the input element to trigger the file selection dialog
	fileInput.click();

}



function buildContextString() {
	const checkboxes = document.querySelectorAll('.LocalGPTcontext[style="background-color: yellow;"]');
	const data = [];
	if (checkboxes.length == 0) {
		return data;
	}
	checkboxes.forEach((checkbox) => {
		const container = checkbox.closest('.LocalGPTrespons-container');
		//console.log(container);
		const role = container.querySelector('.LocalGPTrole').title;
		const content = container.querySelector('.LocalGPTcontext').textContent;
		data.push({ "role": role, "content": content });
	});

	return data;

}

function showStatus(status) {
	// Display the status message
	document.getElementById('status').textContent = status;
	setTimeout(function () {
		document.getElementById('status').textContent = '';
	}, 5000);
}




function loadProfilesFromFile(filename) {
	const xhr = new XMLHttpRequest();
	xhr.open('GET', filename);
	xhr.onload = () => {
		if (xhr.status === 200) {
			const newProfiles = JSON.parse(xhr.responseText);
			//load the profiles and append them at the end of response area
			newProfiles.forEach((profile) => {
				profiles.push(profile);
			});
			//load the profiles to the select box
			profiles.forEach((profile) => {
				const option = document.createElement("option");
				option.text = profile.savecustom;
				option.value = profile.savecustom;
				select.add(option);
			});
		} else {
			console.error(`Failed to [load profiles] from ${filename}: ${xhr.status}`);
		}
	};
	xhr.send();
}


function createProfile(name, content) {
	// create the container div
	const container = document.createElement('div');
	container.classList.add('LocalGPTrespons-container');

	// create the role img
	const roleImg = document.createElement('img');
	roleImg.classList.add('LocalGPTrole');
	roleImg.src = role + '.png';
	roleImg.title = role;
	roleImg.addEventListener('click', function () {
		// rotate the roles "user", "assistant", "system"
		const roles = ["user", "assistant", "system"];
		const currentRole = roles.indexOf(roleImg.title);
		const nextRole = (currentRole + 1) % roles.length;
		roleImg.title = roles[nextRole];
		roleImg.src = roles[nextRole] + '.png';
	});

	// create the context div
	const contextDiv = document.createElement('div');
	contextDiv.classList.add('LocalGPTcontext');
	contextDiv.textContent = context;


	// append the elements to the container
	container.appendChild(roleImg);
	container.appendChild(contextDiv);
	contextDiv.addEventListener('click', function (event) {
		//console.log(this, event.target.parentElement);
		// Check if the div is already selected
		if (this.style.backgroundColor === 'yellow') {
			// Switch back to original color
			this.style.backgroundColor = '';
		} else {
			// Set the background color of the clicked div
			this.style.backgroundColor = 'yellow';
		}
	});

	// return the container element
	return container;
}




function appendProfilelistUI(newprofiles) {
	// Get the profile list element
	const profileList = document.getElementById('LocalGPTprofiles-list');
	// Loop through each profile and create a list item element for it
	newprofiles.forEach(function (profile, index) {
		//profie list 界面需要的内容包括以下：每个profile 包括 1. 标题 2. 内容 3. 一个删除按钮用来删除该profile，删除按钮应该在标题最右侧 点击标题，可以展开或收起关联内容，点击内容，可以直接发送到userinput
		const profileItem = document.createElement('div');
		profileItem.classList.add('LocalGPTprofiles-item');
		profileItem.id = `LocalGPTprofiles-item-${index}`;
	
		const title = document.createElement('div');
		title.classList.add('LocalGPTprofiles-title');
		title.textContent = `${profile.savecustom}`;
		const deleteButton = document.createElement('img');
		deleteButton.classList.add('LocalGPTprofiles-delete');
		deleteButton.src = 'delete.png';
		deleteButton.addEventListener('click', function (event) {
			event.stopPropagation();
			//remove the profile item from the profile list UI
			this.parentElement.parentElement.remove();

		});
		title.appendChild(deleteButton);

		title.addEventListener('click', function () {
			const content = this.nextElementSibling;
			if (content.style.display === 'none') {
				content.style.display = 'block';
			} else {
				content.style.display = 'none';
			}
		});
		profileItem.appendChild(title);

		const content = document.createElement('div');
		content.classList.add('LocalGPTprofiles-content');
		content.textContent = `${profile.saveuser}`;
		content.style.display = 'none';
		content.addEventListener('click', function () {
			document.getElementById("userinput").value = profile.saveuser;
		});
		profileItem.appendChild(content);

		profileList.appendChild(profileItem);

	});
}


function exportProfiles() {
	// Get the profile list element
	const profileList = document.getElementById('LocalGPTprofiles-list');
	console.log(profileList);
	const profiles = [];
	// Loop through each profile and create a list item element for it
	Array.from(profileList.children).forEach(function (profileItem, index) {
		const title = profileItem.querySelector('.LocalGPTprofiles-title');
		const content = profileItem.querySelector('.LocalGPTprofiles-content');
		const savecustom = title.textContent;
		const saveuser = content.textContent;
		profiles.push({ savecustom, saveuser });
	});

	const file = new Blob([JSON.stringify({ profiles })], { type: 'application/json' });
	const url = URL.createObjectURL(file);

	const link = document.createElement('a');
	link.href = url;
	link.download = 'chat_profiles_data.json';
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function importProfiles() {
	const input = document.createElement("input");
	input.type = "file";
	input.accept = ".json,.csv";
  
	// Listen for the change event on the input element
	input.addEventListener("change", (event) => {
	  const file = event.target.files[0];
  
	  // Create a new FileReader object
	  const reader = new FileReader();
  
	  // Listen for the load event on the FileReader object
	  reader.addEventListener("load", () => {
		// Parse the data based on the file type
		let importedProfiles;
		if (file.name.endsWith(".json")) {
		  importedProfiles = JSON.parse(reader.result).profiles;
		} else if (file.name.endsWith(".csv")) {
		  importedProfiles = [];
		  const lines = reader.result.split("\n");
		  for (let i = 0; i < lines.length; i++) {
			const line = lines[i].trim();
			const regex = /"([^"]*)","([^"]*)"/;
			const matches = line.match(regex);
			if (matches) {
			  const savecustom = matches[1].trim();
			  const saveuser = matches[2].trim();
			  importedProfiles.push({ savecustom, saveuser });
			};
		  }
		};
  
		// Merge the imported data into the profiles array
		//profiles = [...importedProfiles, ...profiles];
  
		// Do something with the merged profiles data
		appendProfilelistUI(importedProfiles);
	  });
  
	  // Read the contents of the selected file as a text string
	  reader.readAsText(file);
	});
  
	// Click the input element to trigger the file selection dialog
	input.click();
  }

function addNewPrompt(){
	//pop up a window to ask for the new prompt name
	const newPromptname = prompt("Please enter the new prompt name", "Act as...");
	if (newPromptname != null) {
		const profileList = document.getElementById('LocalGPTprofiles-list');
		// Loop through each profile and create a list item element for it
			const profileItem = document.createElement('div');
			profileItem.classList.add('LocalGPTprofiles-item');
		
			const title = document.createElement('div');
			title.classList.add('LocalGPTprofiles-title');
			title.textContent = newPromptname;
			const deleteButton = document.createElement('img');
			deleteButton.classList.add('LocalGPTprofiles-delete');
			deleteButton.src = 'delete.png';
			deleteButton.addEventListener('click', function (event) {
				event.stopPropagation();
				//remove the profile item from the profile list UI
				this.parentElement.parentElement.remove();
	
			});
			title.appendChild(deleteButton);
	
			title.addEventListener('click', function () {
				const content = this.nextElementSibling;
				if (content.style.display === 'none') {
					content.style.display = 'block';
				} else {
					content.style.display = 'none';
				}
			});
			profileItem.appendChild(title);
	
			const content = document.createElement('div');
			content.classList.add('LocalGPTprofiles-content');
			content.textContent = document.getElementById("userinput").value;
			content.style.display = 'block';
			content.addEventListener('click', function () {
				document.getElementById("userinput").value = content.textContent;
			});
			profileItem.appendChild(content);
			profileList.insertBefore(profileItem, profileList.firstChild);
			profileList.scrollTop = profileItem.offsetTop - profileList.offsetTop;


	
		
	}
}

function cleanupChat(){
	//remove all the chat messages
	const chatList = document.getElementById('response');
	chatList.innerHTML = '';
}

document.addEventListener('keydown', function(event) {
	if (event.ctrlKey && event.keyCode === 13) {
	  makeStreamApiCall();
	}
  });


  function detectPattern(inputString, patternSet) {
    for (const pattern of patternSet) {
      const regex = new RegExp(pattern, 'g');
      if (inputString.match(regex)) {
        return pattern;
      }
    }
    return "Pattern not found";
  }

  function generateArrayOfStrings(inputString, keyword) {
    // Create a regular expression with the keyword and escape any special characters
    const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex = new RegExp(escapedKeyword, 'g');

    // Use the split() method to obtain the array of strings
    const substringsArray = inputString.split(regex);

    return substringsArray;
  }


  function devolverDirReal(query, idOrClk){
    if (query=="Gothic" && destinoViajero=="Barcelona"){
    return "https://www.civitatis.com/es/barcelona/tour-completo-barcelona?aid=103104"+"&cmp="+idOrClk;
    }
   if (query=="Zarautz"){
    return "https://www.civitatis.com/es/gipuzkoa?aid=103104"+"&cmp="+idOrClk;
   }
   if (query=="Hondarribia"){
    return "https://www.civitatis.com/es/gipuzkoa?aid=103104"+"&cmp="+idOrClk;
   }
   if ((query=="San Sebastián")||(query=="San Sebastian")){
    return "https://www.civitatis.com/es/gipuzkoa?aid=103104"+"&cmp="+idOrClk;
   }
   if (query=="Zumaia"){
    return "https://www.civitatis.com/es/gipuzkoa?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Güell"){
     return "https://www.civitatis.com/en/barcelona/guell-park-sagrada-familia-tour?aid=103104"+"&cmp="+idOrClk;
    }
   if(query=="Pamplona"){
     return "https://www.civitatis.com/es/pamplona?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Bilbao"){
     return "https://www.civitatis.com/en/Bilbao?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Barcelona"){
     return "https://www.civitatis.com/en/Barcelona?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Girona"){
     return "https://www.civitatis.com/en/girona?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Tarragona"){
    return "https://www.civitatis.com/en/tarragona?aid=103104"+"&cmp="+idOrClk;
  }
   if(query=="Costa Brava"){
     return "https://www.civitatis.com/en/girona?aid=103104"+"&cmp="+idOrClk;
   }
   if(query=="Seville"){
    return "https://www.civitatis.com/en/seville?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Mallorca"){
    return "https://www.civitatis.com/en/mallorca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ibiza"){
    return "https://www.civitatis.com/en/ibiza?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Zaragoza"){
    return "https://www.civitatis.com/en/zaragoza?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lanzarote"){
    return "https://www.civitatis.com/en/lanzarote?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Basque Country"){
    return "https://www.civitatis.com/en/basque-country?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Costa del Sol"){
    return "https://www.civitatis.com/en/costa-del-sol?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Costa de la Luz"){
    return "https://www.civitatis.com/en/costa-de-la-luz?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Costa Daurada"){
    return "https://www.civitatis.com/en/costa-daurada?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Costa Blanca"){
    return "https://www.civitatis.com/en/costa-blanca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Alicante"){
    return "https://www.civitatis.com/en/alicante?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cantabria"){
    return "https://www.civitatis.com/en/cantabria?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Canary Islands"){
    return "https://www.civitatis.com/en/canary-islands?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Menorca"){
    return "https://www.civitatis.com/en/menorca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Fuerteventura"){
    return "https://www.civitatis.com/en/fuerteventura?aid=103104"+"&cmp="+idOrClk;
  }
  if((query=="Malasaña") || (query=="Chueca")){
    return "https://www.civitatis.com/en/madrid/chueca-malasana-tour?aid=103104"+"&cmp="+idOrClk;
  }
  if((query=="Malaga") || (query=="Málaga")){
    return "https://www.civitatis.com/en/malaga?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ronda"){
    return "https://www.civitatis.com/en/ronda?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Granada"){
    return "https://www.civitatis.com/en/granada?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bali"){
    return "https://www.civitatis.com/en/bali?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Labuan"){
    return "https://www.civitatis.com/en/labuan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Yogyakarta"){
    return "https://www.civitatis.com/en/yogyakarta?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Surabaya"){
    return "https://www.civitatis.com/en/surabaya?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lombok"){
    return "https://www.civitatis.com/en/lombok?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Gili"){
    return "https://www.civitatis.com/en/gili-trawangan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nusa"){
    return "https://www.civitatis.com/en/nusa-penida?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jakarta"){
    return "https://www.civitatis.com/en/yakarta?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Phuket"){
    return "https://www.civitatis.com/en/phuket?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chiang Mai"){
    return "https://www.civitatis.com/en/chiang-mai?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chiang Rai"){
    return "https://www.civitatis.com/en/chiang-rai?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Koh Tao"){
    return "https://www.civitatis.com/en/koh-tao?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Koh Samui"){
    return "https://www.civitatis.com/en/koh-samui?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Phi Phi"){
    return "https://www.civitatis.com/en/ko-phi-phi-don?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bangkok"){
    return "https://www.civitatis.com/en/bangkok?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Khao Lak"){
    return "https://www.civitatis.com/en/khao-lak?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Paris"){
    return "https://www.civitatis.com/en/paris?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bordeaux"){
    return "https://www.civitatis.com/en/bordeaux?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Strasbourg"){
    return "https://www.civitatis.com/en/strasbourg?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Marseille"){
    return "https://www.civitatis.com/en/marseille?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bayeux"){
    return "https://www.civitatis.com/en/bayeux?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Loire"){
    return "https://www.civitatis.com/en/loire?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Biarritz"){
    return "https://www.civitatis.com/en/biarritz?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Saint Jean de Luz"){
    return "https://www.civitatis.com/en/saint-jean-de-luz?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Oporto"){
    return "https://www.civitatis.com/en/oporto?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lisboa"){
    return "https://www.civitatis.com/en/lisboa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Albufeira"){
    return "https://www.civitatis.com/en/albufeira?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Peniche"){
    return "https://www.civitatis.com/en/peniche?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Funchal"){
    return "https://www.civitatis.com/en/funchal?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sintra"){
    return "https://www.civitatis.com/en/sintra?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lagos"){
    return "https://www.civitatis.com/en/lagos?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ponta Delgada"){
    return "https://www.civitatis.com/en/ponta-delgada?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cascais"){
    return "https://www.civitatis.com/en/cascais?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nazaré"){
    return "https://www.civitatis.com/en/nazare?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Estoril"){
    return "https://www.civitatis.com/en/estoril?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Coimbra"){
    return "https://www.civitatis.com/en/coimbra?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Naples"){
    return "https://www.civitatis.com/en/naples?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rome"){
    return "https://www.civitatis.com/en/rome?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Civitavecchia"){
    return "https://www.civitatis.com/en/civitavecchia?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Palermo"){
    return "https://www.civitatis.com/en/palermo?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sorrento"){
    return "https://www.civitatis.com/en/sorrento?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Pompeii"){
    return "https://www.civitatis.com/en/pompeii?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Venice"){
    return "https://www.civitatis.com/en/venice?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Milan"){
    return "https://www.civitatis.com/en/milan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Florence"){
    return "https://www.civitatis.com/en/florence?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bergamo"){
    return "https://www.civitatis.com/en/bergamo?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bari"){
    return "https://www.civitatis.com/en/bari?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="London"){
    return "https://www.civitatis.com/en/london?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Edinburgh"){
    return "https://www.civitatis.com/en/edinburgh?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Dubai"){
    return "https://www.civitatis.com/en/dubai?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Burj Khalifa"){
    return "https://www.civitatis.com/en/dubai/burj-khalifa-lunch-dinner/?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Interlaken"){
    return "https://www.civitatis.com/en/interlaken?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Grindelwald"){
    return "https://www.civitatis.com/en/grindelwald?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Zurich"){
    return "https://www.civitatis.com/en/zurich?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bern"){
    return "https://www.civitatis.com/en/bern?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Montreux"){
    return "https://www.civitatis.com/en/montreux?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Geneva"){
    return "https://www.civitatis.com/en/geneva?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lucerne"){
    return "https://www.civitatis.com/en/lucerne?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Zermatt"){
    return "https://www.civitatis.com/en/zermatt?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lauterbrunnen"){
    return "https://www.civitatis.com/en/lauterbrunnen?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Manama"){
    return "https://www.civitatis.com/en/manama?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Doha"){
    return "https://www.civitatis.com/en/doha?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Hawar"){
    return "https://www.civitatis.com/es/manama/tour-perlas-al-muharraq/"+"&cmp="+idOrClk;
  }
  if(query=="Jodhpur"){
    return "https://www.civitatis.com/en/jodhpur?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chennai"){
    return "https://www.civitatis.com/en/chennai?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Delhi"){
    return "https://www.civitatis.com/en/delhi?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jaipur"){
    return "https://www.civitatis.com/en/jaipur?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Agra"){
    return "https://www.civitatis.com/en/agra?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Varanasi"){
    return "https://www.civitatis.com/en/varanasi?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Munnar"){
    return "https://www.civitatis.com/en/munnar?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chennai"){
    return "https://www.civitatis.com/en/chennai?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Udaipur"){
    return "https://www.civitatis.com/en/udaipur?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Al Ula"){
    return "https://www.civitatis.com/en/al-ula?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Riyadh"){
    return "https://www.civitatis.com/en/riad?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jeddah"){
    return "https://www.civitatis.com/en/yeda?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Medina"){
    return "https://www.civitatis.com/en/medina?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="New York"){
    return "https://www.civitatis.com/en/new-york?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Las Vegas"){
    return "https://www.civitatis.com/en/las-vegas?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="San Francisco"){
    return "https://www.civitatis.com/en/san-francisco?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Miami"){
    return "https://www.civitatis.com/en/miami?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Los Angeles"){
    return "https://www.civitatis.com/en/los-angeles?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Orlando"){
    return "https://www.civitatis.com/en/orlando?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Washington"){
    return "https://www.civitatis.com/en/washington?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Page"){
    return "https://www.civitatis.com/en/page?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Grand Canyon"){
    return "https://www.civitatis.com/en/grand-canon-south-rim/?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cologne"){
    return "https://www.civitatis.com/en/cologne?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nuremberg"){
    return "https://www.civitatis.com/en/nuremberg?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Frankfurt"){
    return "https://www.civitatis.com/en/frankfurt?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kiel"){
    return "https://www.civitatis.com/en/kiel?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Potsdam"){
    return "https://www.civitatis.com/en/potsdam?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Dresden"){
    return "https://www.civitatis.com/en/dresden?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Munich"){
    return "https://www.civitatis.com/en/munich?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Berlin"){
    return "https://www.civitatis.com/en/berlin?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Hamburg"){
    return "https://www.civitatis.com/en/hamburg?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Medellín"){
    return "https://www.civitatis.com/en/medellin?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cartagena de Indias"){
    return "https://www.civitatis.com/en/cartagena-de-indias?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bogotá"){
    return "https://www.civitatis.com/en/bogota?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Santa Marta"){
    return "https://www.civitatis.com/en/santa-marta?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cali"){
    return "https://www.civitatis.com/en/cali?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Pereira"){
    return "https://www.civitatis.com/en/pereira?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Leticia"){
    return "https://www.civitatis.com/en/leticia?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Salento"){
    return "https://www.civitatis.com/en/salento?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="San Andrés"){
    return "https://www.civitatis.com/en/san-andres?aid=103104"+"&cmp="+idOrClk;
  }
  
  if(query=="Toronto"){
    return "https://www.civitatis.com/en/toronto?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Vancouver"){
    return "https://www.civitatis.com/en/vancouver?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Montreal"){
    return "https://www.civitatis.com/en/montreal?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Quebec"){
    return "https://www.civitatis.com/en/quebec?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Tadoussac"){
    return "https://www.civitatis.com/en/tadoussac?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Victoria"){
    return "https://www.civitatis.com/en/victoria?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Whitehorse"){
    return "https://www.civitatis.com/en/whitehorse?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ottawa"){
    return "https://www.civitatis.com/en/ottawa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Niagara Falls"){
    return "https://www.civitatis.com/en/niagara-falls?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Vienna"){
    return "https://www.civitatis.com/en/vienna?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Salzburg"){
    return "https://www.civitatis.com/en/salzburg?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Inssbruck"){
    return "https://www.civitatis.com/en/inssbruck?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Klosterneuburg"){
    return "https://www.civitatis.com/en/klosterneuburg?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Neustift im Stubaital"){
    return "https://www.civitatis.com/en/neustift-im-stubaital?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Eisenstadt"){
    return "https://www.civitatis.com/en/eisenstadt?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Seoul"){
    return "https://www.civitatis.com/en/seoul?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Busan"){
    return "https://www.civitatis.com/en/busan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jeju"){
    return "https://www.civitatis.com/en/jeju?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Andong"){
    return "https://www.civitatis.com/en/andong?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Daegu"){
    return "https://www.civitatis.com/en/daegu?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jerusalem"){
    return "https://www.civitatis.com/en/jerusalem?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Tel Aviv"){
    return "https://www.civitatis.com/en/tel-aviv?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ashdod"){
    return "https://www.civitatis.com/en/asdod?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Haifa"){
    return "https://www.civitatis.com/en/haifa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nazareth"){
    return "https://www.civitatis.com/en/nazareth?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Cuzco"){
    return "https://www.civitatis.com/en/cuzco?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Lima"){
    return "https://www.civitatis.com/en/lima?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Iquitos"){
    return "https://www.civitatis.com/en/iquitos?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Puno"){
    return "https://www.civitatis.com/en/puno?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Arequipa"){
    return "https://www.civitatis.com/en/arequipa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nazca"){
    return "https://www.civitatis.com/en/nazca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Paracas"){
    return "https://www.civitatis.com/en/paracas?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ica"){
    return "https://www.civitatis.com/en/ica?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Aguas Calientes"){
    return "https://www.civitatis.com/en/aguas-calientes?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="El Nido"){
    return "https://www.civitatis.com/en/el-nido?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Manila"){
    return "https://www.civitatis.com/en/manila?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bohol"){
    return "https://www.civitatis.com/en/bohol?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Coron"){
    return "https://www.civitatis.com/en/coron?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Puerto Princesa"){
    return "https://www.civitatis.com/en/puerto-princesa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cebu"){
    return "https://www.civitatis.com/en/cebu?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Boracay"){
    return "https://www.civitatis.com/en/boracay?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Port Barton"){
    return "https://www.civitatis.com/en/port-barton?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Siargao"){
    return "https://www.civitatis.com/en/siargao?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Aruba"){
    return "https://www.civitatis.com/en/aruba?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Dublin"){
    return "https://www.civitatis.com/en/dublin?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Galway"){
    return "https://www.civitatis.com/en/galway?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cork"){
    return "https://www.civitatis.com/en/cork?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Killarney"){
    return "https://www.civitatis.com/en/killarney?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Aillemore"){
    return "https://www.civitatis.com/en/aillemore?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rossaveel"){
    return "https://www.civitatis.com/en/rossaveel?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kilkenny"){
    return "https://www.civitatis.com/en/kilkenny?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Letterfrack"){
    return "https://www.civitatis.com/en/letterfrack?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kilkieran"){
    return "https://www.civitatis.com/en/kilkieran?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nassau"){
    return "https://www.civitatis.com/en/nassau?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Tokyo"){
    return "https://www.civitatis.com/en/tokyo?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kyoto"){
    return "https://www.civitatis.com/en/kyoto?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Osaka"){
    return "https://www.civitatis.com/en/osaka?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Hiroshima"){
    return "https://www.civitatis.com/en/hiroshima?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Nara"){
    return "https://www.civitatis.com/en/nara?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Miyajima"){
    return "https://www.civitatis.com/en/miyajima?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Shirakawa"){
    return "https://www.civitatis.com/en/shirakawa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Takayama"){
    return "https://www.civitatis.com/en/takayama?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kanazawa"){
    return "https://www.civitatis.com/en/kanazawa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Arenal"){
    return "https://www.civitatis.com/en/arenal?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="San José"){
    return "https://www.civitatis.com/en/san-jose?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Manuel Antonio"){
    return "https://www.civitatis.com/en/manuel-antonio?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Monteverde"){
    return "https://www.civitatis.com/en/monteverde?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Guanacaste"){
    return "https://www.civitatis.com/en/guanacaste?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="La Fortuna"){
    return "https://www.civitatis.com/en/la-fortuna?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bahía Drake"){
    return "https://www.civitatis.com/en/bahia-drake?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sierpe"){
    return "https://www.civitatis.com/en/sierpe?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rincón de la Vieja"){
    return "https://www.civitatis.com/en/rincon-de-la-vieja?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Marrakech"){
    return "https://www.civitatis.com/en/marrakech?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Fez"){
    return "https://www.civitatis.com/en/fez?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Agadir"){
    return "https://www.civitatis.com/en/agadir?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Tangier"){
    return "https://www.civitatis.com/en/tangier?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Merzouga"){
    return "https://www.civitatis.com/en/merzouga?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Casablanca"){
    return "https://www.civitatis.com/en/casablanca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chefchaouen"){
    return "https://www.civitatis.com/en/chefchaouen?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Essaouira"){
    return "https://www.civitatis.com/en/essaouira?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Belfast"){
    return "https://www.civitatis.com/en/belfast?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Liverpool"){
    return "https://www.civitatis.com/en/liverpool?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Inverness"){
    return "https://www.civitatis.com/en/inverness?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Glasgow"){
    return "https://www.civitatis.com/en/glasgow?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Oxford"){
    return "https://www.civitatis.com/en/oxford?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cairns"){
    return "https://www.civitatis.com/en/cairns?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sydney"){
    return "https://www.civitatis.com/en/sydney?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Yulara"){
    return "https://www.civitatis.com/en/yulara?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Gold Coast"){
    return "https://www.civitatis.com/en/gold-coast?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Airlie Beach"){
    return "https://www.civitatis.com/en/airlie-beach?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Perth"){
    return "https://www.civitatis.com/en/perth?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Darwin"){
    return "https://www.civitatis.com/en/darwin?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Hobart"){
    return "https://www.civitatis.com/en/hobart?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Athens"){
    return "https://www.civitatis.com/en/athens?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Santorini"){
    return "https://www.civitatis.com/en/santorini?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Milos"){
    return "https://www.civitatis.com/en/milos?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Mykonos"){
    return "https://www.civitatis.com/en/mykonos?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Corfu"){
    return "https://www.civitatis.com/en/corfu?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rodhes"){
    return "https://www.civitatis.com/en/rodhes?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Kalambaka"){
    return "https://www.civitatis.com/en/kalambaka?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Zante"){
    return "https://www.civitatis.com/en/zante?aid=103104"+"&cmp="+idOrClk;
  }

  if(query=="Amsterdam"){
    return "https://www.civitatis.com/en/amsterdam?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rotterdam"){
    return "https://www.civitatis.com/en/rotterdam?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Oranjestad"){
    return "https://www.civitatis.com/en/oranjestad?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Utrecht"){
    return "https://www.civitatis.com/en/utrecht?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="The Haghe"){
    return "https://www.civitatis.com/en/the-hague?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Willemstad"){
    return "https://www.civitatis.com/en/willemstad?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Volendam"){
    return "https://www.civitatis.com/en/volendam?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Delft"){
    return "https://www.civitatis.com/en/delft?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Marken"){
    return "https://www.civitatis.com/en/delft?aid=103104"+"&cmp="+idOrClk;
  }
  

  if(query=="Krakow"){
    return "https://www.civitatis.com/en/krakow?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Warsaw"){
    return "https://www.civitatis.com/en/warsaw?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Wroclaw"){
    return "https://www.civitatis.com/en/wroclaw?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Gdansk"){
    return "https://www.civitatis.com/en/gdansk?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Oswiecim"){
    return "https://www.civitatis.com/en/oswiecim?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Katowice"){
    return "https://www.civitatis.com/en/katowice?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Poznan"){
    return "https://www.civitatis.com/en/poznan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Torun"){
    return "https://www.civitatis.com/en/torun?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Szczecin"){
    return "https://www.civitatis.com/en/szczecin?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Prague"){
    return "https://www.civitatis.com/en/prague?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cesky Krumlov"){
    return "https://www.civitatis.com/en/cesky-krumlov?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Punta Cana"){
    return "https://www.civitatis.com/en/punta-cana?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Samana"){
    return "https://www.civitatis.com/en/samana?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="La Romana"){
    return "https://www.civitatis.com/en/la-romana?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Juan Dolio"){
    return "https://www.civitatis.com/en/juan-dolio?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Jarabacoa"){
    return "https://www.civitatis.com/en/jarabacoa?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Boca Chica"){
    return "https://www.civitatis.com/en/boca-chica?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Puerto Plata"){
    return "https://www.civitatis.com/en/puerto-plata?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Santo Domingo"){
    return "https://www.civitatis.com/en/santo-domingo?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Mexico City"){
    return "https://www.civitatis.com/en/mexico-city?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Merida"){
    return "https://www.civitatis.com/en/merida?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Oaxaca"){
    return "https://www.civitatis.com/en/oaxaca?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cozumel"){
    return "https://www.civitatis.com/en/cozumel?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Tulum"){
    return "https://www.civitatis.com/en/tulum?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Cancun"){
    return "https://www.civitatis.com/en/cancun?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Puerto Morelos"){
    return "https://www.civitatis.com/en/puerto-morelos?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Cairo"){
    return "https://www.civitatis.com/en/cairo?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Aswan"){
    return "https://www.civitatis.com/en/aswan?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Luxor"){
    return "https://www.civitatis.com/en/luxor?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sharm el Sheikh"){
    return "https://www.civitatis.com/en/sharm-el-sheikh?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Hurghada"){
    return "https://www.civitatis.com/en/hurghada?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Alexandria"){
    return "https://www.civitatis.com/en/alexandria?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Giza"){
    return "https://www.civitatis.com/en/giza?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Marsa Alam"){
    return "https://www.civitatis.com/en/marsa-alam?aid=103104"+"&cmp="+idOrClk;
  }
   
  if(query=="Copenhagen"){
    return "https://www.civitatis.com/en/copenhagen?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Sofia"){
    return "https://www.civitatis.com/en/sofia?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Plovdiv"){
    return "https://www.civitatis.com/en/plovdiv?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bucharest"){
    return "https://www.civitatis.com/en/bucharest?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Brasov"){
    return "https://www.civitatis.com/en/brasov?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Rovaniemi"){
    return "https://www.civitatis.com/en/rovaniemi?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Helsinki"){
    return "https://www.civitatis.com/en/helsinki?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Stockholm"){
    return "https://www.civitatis.com/en/stockholm?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Malmö"){
    return "https://www.civitatis.com/en/malmo?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Oslo"){
    return "https://www.civitatis.com/en/oslo?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Bergen"){
    return "https://www.civitatis.com/en/bergen?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Stavanger"){
    return "https://www.civitatis.com/en/stavanger?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="El Calafate"){
    return "https://www.civitatis.com/en/el-calafate?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Buenos Aires"){
    return "https://www.civitatis.com/en/buenos-aires?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Ushuaia"){
    return "https://www.civitatis.com/en/ushuaia?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Bariloche"){
    return "https://www.civitatis.com/en/bariloche?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Puerto Iguazu"){
    return "https://www.civitatis.com/en/puerto-iguazu?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Santiago de Chile"){
    return "https://www.civitatis.com/en/santiago-de-chile?aid=103104"+"&cmp="+idOrClk;
  } 
  if(query=="Atacama"){
    return "https://www.civitatis.com/en/san-pedro-de-atacama?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Puerto Natales"){
    return "https://www.civitatis.com/en/puerto-natales?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Punta Arenas"){
    return "https://www.civitatis.com/en/punta-arenas?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Eastern Island"){
    return "https://www.civitatis.com/en/easter-island?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Reykjavik"){
    return "https://www.civitatis.com/en/reykjavik?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Skaftafell"){
    return "https://www.civitatis.com/en/skaftafell?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Husavik"){
    return "https://www.civitatis.com/en/husavik?aid=103104"+"&cmp="+idOrClk;
  }
  if(query=="Chichen Itza"){
    return "https://www.civitatis.com/en/chichen-itza?aid=103104"+"&cmp="+idOrClk;
  }
   else
     return "";
  }
  function htmlEncode(input) {
	var idOrigenClick=0;
	const textArea = document.createElement("textarea");
	//hacer el addDestinations con un input determinado
	var splittedInput= input;
	splittedInput =input.split('\n');//obtenemos las líneas
	for(let i=0;i<splittedInput.length;i++){
		if(splittedInput[i].includes("Day")){ //si es título
			//ver si es destino primario
			var miActividad4= detectPattern(splittedInput[i],patternSetNivelCuatro);

            if(!(miActividad4 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad4);
				splittedInput.splice(i+1,0,'<h2>===</h2>')
				splittedInput.splice(i + 2, 0, '<img src="./img/'+miActividad4+'.jpg" style="width: 100%; margin-top: -20px;margin-bottom: 20px;height: auto;max-height: 400px;border-radius: 10px;object-fit: cover;object-position: center;""></a>');
                splittedInput.splice(i+3,0,'<a href="'+devolverDirReal(miActividad4, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad4+'</a>');
            }
            //vamos a por la 3
            var miActividad3= detectPattern(splittedInput[i],patternSetNivelTres);

            if(!(miActividad3 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad3);
				splittedInput.splice(i+1,0,'<h2>====</h2>')
				splittedInput.splice(i + 2, 0,'<img src="./img/'+miActividad3+'.jpg">');
				splittedInput.splice(i + 3, 0,'<a href="'+devolverDirReal(miActividad3, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad3+'</a>');
            }
            //ahora a por la dos
            var miActividad2= detectPattern(splittedInput[i],patternSetNivelDos);

            if(!(miActividad2 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad2);
				splittedInput.splice(i+1,0,'<h2>===</h2>')
				splittedInput.splice(i + 2, 0,'<img src="./img/'+miActividad2+'.jpg">');
				splittedInput.splice(i + 3, 0, '<a href="'+devolverDirReal(miActividad2, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad2+'</a>');

            }
            //y finalmente a por la uno
            var miActividad1= detectPattern(splittedInput[i],patternSetNivelUno);

            if(!(miActividad1 =="Pattern not found")){
                //es decir, hay un lugar de tipo 4 en el texto, obtenemos el link correspondiente y ponemos la imagen
                //ahora obtenemos el link
                console.log(miActividad1);
				splittedInput.splice(i+1,0,'<h2>====</h2>')
				splittedInput.splice(i + 2, 0,'<img src="././img/'+miActividad1+'.jpg">');
				splittedInput.splice(i + 3, 0,'<a href="'+devolverDirReal(miActividad1, idOrigenClick)+'" class="booknow" target="_blank">Book Tour around '+miActividad1+'</a>');
            }
        }

	  }
	var churro= splittedInput.join("<br>");
	textArea.innerText = churro;
	
	//textArea.innerText.replace('&lt;','<').replace('&gt;','>');textArea.innerHTML.split("<br>").join("\n");
	return textArea.innerText;
  }
  function addDestinations(originalString, targetWord, newText) {
	const lines = originalString.split('\n');
	
	for (let i = 0; i < lines.length; i++) {
	  if (lines[i].includes(targetWord)) {
		lines.splice(i + 1, 0, newText);
		break;
	  }
	}
	
	return lines.join('\n');
  }