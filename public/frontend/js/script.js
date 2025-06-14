document.addEventListener('DOMContentLoaded', function() {
    // Máscara para o campo de telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 2 && value.length <= 6) {
                value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else if (value.length > 6) {
                value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
            }
            
            e.target.value = value;
        });
    }
    
    // Máscara para o campo de valor do veículo
    const valueInput = document.getElementById('vehicleValue');
    if (valueInput) {
        valueInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value === '') return e.target.value = '';
            
            value = parseInt(value, 10).toString();
            e.target.value = new Intl.NumberFormat('pt-BR').format(value);
        });
    }
    
    // Envio do formulário para WhatsApp
    const leadForm = document.getElementById('leadForm');
    if (leadForm) {
        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Coleta os dados do formulário
            const vehicleType = document.getElementById('vehicleType').value;
            const vehicleModel = document.getElementById('vehicleModel').value;
            const vehicleYear = document.getElementById('vehicleYear').value;
            const vehicleValue = document.getElementById('vehicleValue').value;
            const vehicleWorking = document.querySelector('input[name="vehicleWorking"]:checked').value;
            const vehicleArmored = document.querySelector('input[name="vehicleArmored"]:checked').value;
            const originCity = document.getElementById('originCity').value;
            const destinationCity = document.getElementById('destinationCity').value;
            const serviceType = document.querySelector('input[name="serviceType"]:checked').value;
            const transportUrgency = document.querySelector('input[name="transportUrgency"]:checked').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            // Monta a mensagem
            let message = `*Nova Solicitação de Cotação - Transcar*\n\n`;
            message += `*Dados do Veículo:*\n`;
            message += `- Tipo: ${vehicleType}\n`;
            message += `- Modelo: ${vehicleModel}\n`;
            message += `- Ano: ${vehicleYear}\n`;
            message += `- Valor: R$ ${vehicleValue}\n`;
            message += `- Em funcionamento: ${vehicleWorking}\n`;
            message += `- Blindado: ${vehicleArmored}\n\n`;
            
            message += `*Informações de Transporte:*\n`;
            message += `- Origem: ${originCity}\n`;
            message += `- Destino: ${destinationCity}\n`;
            message += `- Serviço: ${serviceType}\n`;
            message += `- Urgência: ${transportUrgency}\n\n`;
            
            message += `*Dados do Cliente:*\n`;
            message += `- Nome: ${name}\n`;
            message += `- Email: ${email}\n`;
            message += `- Telefone: ${phone}\n\n`;
            
            message += `Aguardo contato para finalizar a cotação.`;
            
            // Codifica a mensagem para URL
            const encodedMessage = encodeURIComponent(message);
            
            // Cria o link do WhatsApp
            const whatsappLink = `https://api.whatsapp.com/send?phone=5571993735177&text=${encodedMessage}`;
            
            // Redireciona para o WhatsApp
            window.open(whatsappLink, '_blank');
            
            // Redireciona para a página de agradecimento
            window.location.href = 'obrigado.html';
            
            // Exibe mensagem de sucesso
            showSuccessMessage();
        });
    }
    
    // Inicializar notificações de atividade recente
    initRecentActivity();
});

// Adicionar esta função no final do arquivo
function initAnimations() {
    // Verificar se o elemento está visível na viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Adicionar classe de animação quando o elemento estiver visível
    function handleScrollAnimations() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        animatedElements.forEach(element => {
            if (isElementInViewport(element) && !element.classList.contains('animated')) {
                element.classList.add('animated');
            }
        });
    }

    // Adicionar evento de scroll
    window.addEventListener('scroll', handleScrollAnimations);
    
    // Verificar elementos visíveis no carregamento inicial
    handleScrollAnimations();
}

// Inicializar animações
initAnimations();



// Adicionar esta função após as outras funções
function initRecentActivity() {
    const container = document.getElementById('recent-activity-container');
    
    // Lista de nomes fictícios
    const names = [
        'Marcos', 'Ana', 'Carlos', 'Juliana', 'Roberto', 'Fernanda', 
        'Lucas', 'Mariana', 'Pedro', 'Camila', 'João', 'Beatriz', 
        'Rafael', 'Larissa', 'Gustavo', 'Amanda', 'Felipe', 'Patrícia',
        'Bruno', 'Daniela', 'Rodrigo', 'Natália', 'Thiago', 'Vanessa'
    ];
    
    // Lista de cidades de origem
    const originCities = [
        'São Paulo', 'Peruíbe', 'Santos', 'Praia Grande'
    ];
    
    // Lista de cidades de destino
    const destinationCities = [
        'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Salvador', 
        'Brasília', 'Fortaleza', 'Recife', 'Porto Alegre', 'Curitiba', 
        'Manaus', 'Belém', 'Goiânia', 'Florianópolis', 'Vitória'
    ];
    
    // Lista de tipos de veículos
    const vehicleTypes = [
        'Carro de Passeio', 'Picape', 'Caminhonete', 'Moto', 'Sedan'
    ];
    
    // Função para gerar um item aleatório de uma lista
    function getRandomItem(list) {
        return list[Math.floor(Math.random() * list.length)];
    }
    
    // Função para gerar uma notificação de atividade recente
    function createActivityNotification() {
        // Selecionar dados aleatórios
        const name = getRandomItem(names);
        const origin = getRandomItem(originCities);
        const destination = getRandomItem(destinationCities);
        const vehicleType = getRandomItem(vehicleTypes);
        
        // Evitar origem e destino iguais
        let finalDestination = destination;
        while (finalDestination === origin) {
            finalDestination = getRandomItem(destinationCities);
        }
        
        // Criar elemento de notificação
        const notification = document.createElement('div');
        notification.className = 'activity-notification';
        
        // Obter as iniciais do nome
        const initials = name.charAt(0);
        
        // Gerar conteúdo da notificação com design mais moderno
        notification.innerHTML = `
            <div class="activity-avatar">${initials}</div>
            <div class="activity-content">
                <p><span class="activity-name">${name}</span> acabou de comprar um veículo</p>
                <p class="activity-time">Agora mesmo</p>
            </div>
            <button class="activity-close" aria-label="Fechar notificação">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Adicionar ao container
        container.appendChild(notification);
        
        // Adicionar evento de clique no botão de fechar
        const closeButton = notification.querySelector('.activity-close');
        closeButton.addEventListener('click', function() {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        // Mostrar a notificação com um pequeno atraso
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Remover a notificação após alguns segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 5000);
    }
    
    // Mostrar a primeira notificação após um curto período
    setTimeout(createActivityNotification, 5000);
    
    // Mostrar notificações em intervalos aleatórios
    function scheduleNextNotification() {
        // Intervalo aleatório entre 15 e 45 segundos
        const nextInterval = Math.floor(Math.random() * 30000) + 15000;
        
        setTimeout(() => {
            createActivityNotification();
            scheduleNextNotification();
        }, nextInterval);
    }
    
    // Iniciar o agendamento de notificações
    scheduleNextNotification();
} 