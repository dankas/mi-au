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
  notificacao.style.cssText =
    "background-color: #fdecdf; border-left: 6px solid #F28B0C; color: #020E26; margin: 0 20px 15px 20px; padding: 15px; display: none; border-radius: 8px;";

  // Pega o nome de cada pet perdido e junta com vírgula.
  const nomesPetsPerdidos = petsPerdidos.map((pet) => pet.nome).join(", ");
  notificacao.innerHTML = `<strong>Atenção:</strong> Pet(s) perdido(s): ${nomesPetsPerdidos}. Ajude a encontrá-los!`;
  notificacao.style.display = "block";

  // Adiciona a notificação no início do container principal.
  container.prepend(notificacao);
}

// Função para popular o menu de pets com o novo estilo.
function montarMenuPets(pets, elementoMenu) {
  let menuHtml = "";
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