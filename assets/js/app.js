// Função para buscar e converter dados JSON, com tratamento de erro.
async function buscarDados(url) {
  const resposta = await fetch(url);
  // Se a resposta não for "ok" (ex: erro 404), lança um erro.
  if (!resposta.ok) {
    throw new Error(`Erro ao buscar ${url}: ${resposta.statusText}`);
  }
  // Converte a resposta para JSON e a retorna.
  return resposta.json();
}

// Função para criar e exibir a notificação de pet perdido.
function exibirNotificacaoPetPerdido(pets, container) {
  // Filtra a lista de pets para encontrar apenas os que estão marcados como perdidos.
  const petsPerdidos = pets.filter((pet) => pet.perdido === 1);

  // Se não houver pets perdidos, não faz nada.
  if (petsPerdidos.length === 0) {
    return;
  }

  // Cria o elemento da notificação.
  const notificacao = document.createElement("div");
  notificacao.id = "lostPetNotification"; // Mantém o ID para o CSS
  // O estilo foi movido para assets/css/components.css

  // Pega o nome de cada pet perdido e junta com vírgula.
  const nomesPetsPerdidos = petsPerdidos.map((pet) => pet.nome).join(", ");
  notificacao.innerHTML = `<strong>Atenção:</strong> Pet(s) perdido(s): ${nomesPetsPerdidos}. <strong>Clique aqui para ver o mapa e ajudar!</strong>`;
  notificacao.style.display = "block"; // A visibilidade ainda é controlada aqui

  // Adiciona o evento de clique para abrir o modal com o mapa.
  notificacao.addEventListener("click", mostrarMapaDePetsPerdidos);

  // Adiciona a notificação no início do container principal.
  container.prepend(notificacao);
}

// Função para popular o menu de pets com o novo estilo.
function montarMenuPets(pets, elementoMenu) {
  let menuHtml = "<li> <img class=\"header-logo\" src=\"assets/imgs/logos/logo.png\" alt=\"MiAu Logo\" /> </li>";
  // Usando um laço 'for...of' para construir o HTML, uma abordagem mais explícita.
  for (const pet of pets) {
    menuHtml += `
        <li>
            <a href="#${pet.idpet}" style="background-image: url('assets/imgs/uploads/${pet.imgperfil}')" title="Ver detalhes de ${pet.nome}">
                <span class="pet-menu-name">${pet.nome}</span>
            </a>
        </li>
    `;
  }
  elementoMenu.innerHTML = menuHtml;
}

function exibirDetalhesPet(pet, tutor, consultas, fotos, elementoConteudo) {
  // Set para garantir que não haja imagens duplicadas.(https://pt.stackoverflow.com/questions/16483/remover-elementos-repetido-dentro-de-um-array-em-javascript#16487)
  const todasAsImagens = [...new Set([pet.imgperfil, ...fotos.map((f) => f.img)])];

  //informações do pet e do tutor.
  const htmlInfo = `
        <div class="pet-info">
            <h2>${pet.nome}</h2>
            <p><strong>Tipo:</strong> ${pet.tipo || "Não informado"}</p>
            <p><strong>Raça:</strong> ${pet.race || "Não informada"}</p>
            <p><strong>Nascimento:</strong> ${new Date(pet.nascimento).toLocaleDateString()}</p>
            <p><strong>Bio:</strong> ${pet.bio || "Sem biografia."}</p>
            ${tutor
              ? `
                <h3>Tutor</h3>
                <p><strong>Nome:</strong> ${tutor.username}</p>
                <p><strong>Telefone:</strong> ${tutor.telefone || "Não informado"}</p>
              `
              : ""
            }
        </div>
    `;

  // galeria de fotos.
  const htmlFotos = `
        <div class="pet-photos">
            <h3>Fotos</h3>
            <div class="pet-photos-grid">
                ${todasAsImagens.map((img) => `<img src="assets/imgs/uploads/${img}" alt="Foto de ${pet.nome}">`).join("")}
            </div>
            ${todasAsImagens.length === 0 ? "<p>Não há fotos.</p>" : ""}
        </div>
    `;

  //  histórico de consultas.
  const htmlConsultas = `
        <div class="pet-consultations">
            <h3>Consultas</h3>
            ${consultas.length > 0
              ? `<ul>
                    ${consultas.map((c) => `
                        <li>
                            <strong>Data:</strong> ${new Date(c.dataconsulta).toLocaleDateString()},
                            <strong>Veterinário:</strong> ${c.nomevet},
                            <strong>Tipo:</strong> ${c.nome},
                            <strong>Descrição:</strong> ${c.descricao}
                            ${c.img ? `<br><img src="assets/imgs/uploads/${c.img}" alt="Imagem da Consulta" class="consultation-img">` : ""}
                        </li>
                    `).join("")}
                </ul>`
              : `<p>Nenhuma consulta registrada para ${pet.nome}.</p>`
            }
        </div>
    `;

  // Junta todos os blocos de HTML
  elementoConteudo.innerHTML = htmlInfo + htmlFotos + htmlConsultas;
  elementoConteudo.classList.remove("no-content");
}

// Função para popular o rodapé com as informações do tutor.
function montarRodape(usuario, elementoRodape) {
  if (!usuario || !elementoRodape) return;

  elementoRodape.innerHTML = `
        <div class="footer-user-info">
            <p>Tutor: ${usuario.username}</p>
            ${usuario.telefone ? `<p>Contato: ${usuario.telefone}</p>` : ""}
        </div>
        <p>&copy; ${new Date().getFullYear()} MiAu. Todos os direitos reservados.</p>
    `;
}

// Variável global para guardar a instância do mapa e evitar reinicialização.
let mapInstance = null;

// Função para criar o modal do mapa, se ele ainda não existir.
function criarModalDoMapa() {
  // Se o modal já existe no DOM, não faz nada.
  if (document.getElementById("mapModal")) {
    return;
  }

  // Cria o HTML do modal.
  const modalHtml = `
    <div id="mapModal">
      <div id="mapModalContent">
        <span id="closeMapModal">&times;</span>
        <h2>Localização para Busca</h2>
        <p>O mapa abaixo mostra sua localização atual para ajudar na busca pelo pet perdido.</p>
        <div id="map">Carregando mapa...</div>
        <div class="map-actions">
          <a id="whatsapp-share-link" href="#" target="_blank">Compartilhar Localização no WhatsApp</a>
        </div>
      </div>
    </div>
  `;

  // Adiciona o HTML do modal ao final do body.
  document.body.insertAdjacentHTML('beforeend', modalHtml);

  const modal = document.getElementById("mapModal");
  const span = document.getElementById("closeMapModal");

  // Função para fechar o modal.
  const fecharModal = () => {
    modal.style.display = "none";
  };

  // Eventos para fechar o modal.
  span.onclick = fecharModal;
  window.addEventListener("click", (event) => {
    if (event.target == modal) {
      fecharModal();
    }
  });
}

// Função para mostrar o mapa com a localização do usuário usando OpenStreetMap.
function mostrarMapaDePetsPerdidos() {
  // Garante que o modal exista no DOM.
  criarModalDoMapa();

  const modal = document.getElementById("mapModal");
  const mapDiv = document.getElementById("map");
  const whatsappLink = document.getElementById("whatsapp-share-link");

  // Reseta e esconde o botão do WhatsApp ao abrir o modal
  if (whatsappLink) {
    whatsappLink.style.display = "none";
    whatsappLink.href = "#";
  }
  modal.style.display = "block"; // Exibe o modal.
  mapDiv.innerHTML = "Obtendo sua localização..."; // Mensagem de feedback

  // Verifica se a biblioteca Leaflet foi carregada.
  if (typeof L === 'undefined') {
    console.error("A biblioteca Leaflet.js não foi carregada.");
    mapDiv.innerHTML = 'Erro ao carregar o mapa. Verifique a inclusão da biblioteca Leaflet.';
    return;
  }

  // Tenta obter a localização do usuário.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        const userLocation = [lat, lon];

        // Se o mapa não foi inicializado, cria a instância.
        if (!mapInstance) {
          mapInstance = L.map('map');
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(mapInstance);
        }
        mapInstance.setView(userLocation, 15); // Centraliza o mapa na localização do usuário.
        L.marker(userLocation).addTo(mapInstance).bindPopup('<b>Você está aqui!</b><br>Área de busca inicial.').openPopup();


        setTimeout(() => mapInstance.invalidateSize(), 150);
        // Cria o link do Google Maps com as coordenadas do usuário.
        const googleMapsLink = `https://www.google.com/maps?q=${lat},${lon}`;
        
        // Cria a mensagem para o WhatsApp, incluindo o link do mapa, e a codifica para URL.
        const whatsappMessage = encodeURIComponent(`Olá! Vi o aviso de pet perdido e quero ajudar. Minha localização para a busca é: ${googleMapsLink}`);
        
        // Monta o link final do WhatsApp com o número e a mensagem.
        if (whatsappLink) {
            whatsappLink.href = `https://wa.me/5553981323457?text=${whatsappMessage}`;
            // Exibe o botão do WhatsApp.
            whatsappLink.style.display = "inline-block";
        }
      },
      () => { 
        mapDiv.innerHTML = "Não foi possível obter sua localização. Por favor, habilite o serviço de localização no seu navegador e tente novamente."; 
        if (whatsappLink) whatsappLink.style.display = "none";
      }
    );
  } else { 
    mapDiv.innerHTML = "Geolocalização não é suportada por este navegador."; 
    if (whatsappLink) whatsappLink.style.display = "none";
  }
}

//configuração do app.
async function iniciarAplicacao() {
  // Pega os elementos da página que vamos manipular.
  const elementoMenuPets = document.getElementById("petMenu");
  const elementoConteudoPet = document.getElementById("petContent");
  const elementoPrincipal = document.querySelector("main");
  const elementoRodape = document.getElementById("page-footer");

  
  try {
    // Carrega Json por promisse
    // https://developer.mozilla.org/pt-BR/docs/Learn_web_development/Extensions/Async_JS/Promises
    const [dadosUsuario, pets, consultas, fotos] = await Promise.all([
      buscarDados("export/user.json"),
      buscarDados("export/pets.json"),
      buscarDados("export/consultas.json"),
      buscarDados("export/fotos.json"),
    ]);

    
    const usuarioPrincipal = Array.isArray(dadosUsuario) ? dadosUsuario[0] : dadosUsuario;

    // Monta página.
    montarMenuPets(pets, elementoMenuPets);
    montarRodape(usuarioPrincipal, elementoRodape);
    exibirNotificacaoPetPerdido(pets, elementoPrincipal);

    // 
    const aoMudarRota = () => {
      
      document.querySelectorAll(".pet-menu a").forEach((a) => a.classList.remove("selected"));

      // ignora #
      const idDoPet = window.location.hash.substring(1);

      if (idDoPet) {
        // lista de pets pelo ID.
        const petSelecionado = pets.find((p) => p.idpet == idDoPet);

        if (petSelecionado) {
          // Encontra o tutor do pet.
          // prepara para ser um array de users
          const listaDeUsuarios = Array.isArray(dadosUsuario) ? dadosUsuario : [dadosUsuario];
          const tutorDoPet = listaDeUsuarios.find((u) => u.iduser == petSelecionado.tutor);

          // Filtra as consultas e fotos.
          const consultasDoPet = consultas.filter((c) => c.pet == idDoPet);
          const fotosDoPet = fotos.filter((f) => f.idpet == idDoPet);

          // detalhes
          exibirDetalhesPet(petSelecionado, tutorDoPet, consultasDoPet, fotosDoPet, elementoConteudoPet);
          document.querySelector(`.pet-menu a[href="#${idDoPet}"]`)?.classList.add("selected");
        }
      } else {
        // Se não houver ID na URL, mostra a mensagem de boas-vindas.
        elementoConteudoPet.innerHTML =
          "<h2>Bem-vindo ao MiAu!</h2><p>Selecione um pet no menu acima para ver seus detalhes.</p>";
        elementoConteudoPet.classList.add("no-content");
      }
    };

 
    window.addEventListener("hashchange", aoMudarRota);
    aoMudarRota();
  } catch (error) {
    console.error("Falha ao inicializar a aplicação:", error);
    elementoConteudoPet.innerHTML =
      '<div class="no-content"><h2>Ops!</h2><p>Ocorreu um erro ao carregar as informações. Por favor, tente novamente mais tarde.</p></div>';
  }
}

// Inicia a aplicação
iniciarAplicacao();