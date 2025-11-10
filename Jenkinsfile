pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = credentials('docker-hub')
        IMAGE_NAME = "dark093/gestor-php"        
        BUILD_VERSION = "1.0.${env.BUILD_ID}"
    }

    stages {

        stage('Checkout') {
            steps {
                echo "Clonando repositorio..."
                git branch: 'main', url: 'https://github.com/juan2344/ultimoparcial.git'
            }
        }

        stage('Instalar dependencias PHP') {
            steps {
                echo "Instalando dependencias con Composer..."
                sh """
                    if [ -f composer.json ]; then
                        docker run --rm -v \$(pwd):/app -w /app composer install --no-interaction --prefer-dist
                    else
                        echo 'No se encontró composer.json, se omite instalación.'
                    fi
                """
            }
        }   
        stage('Detect Changes') {
            steps {
                script {
                    echo "Verificando cambios desde el último despliegue..."

                    // Último commit actual
                    def currentCommit = sh(script: "git rev-parse HEAD", returnStdout: true).trim()
                    def commitFile = "${env.WORKSPACE}/.last_commit"

                    if (fileExists(commitFile)) {
                        def lastCommit = readFile(commitFile).trim()
                        if (currentCommit == lastCommit) {
                            echo "No hay cambios nuevos desde el último despliegue (${lastCommit})."
                            currentBuild.result = 'SUCCESS'
                            currentBuild.displayName = "Sin cambios"
                            // Detiene el pipeline limpiamente
                            error("No se detectaron cambios nuevos, se detiene el pipeline.")
                        } else {
                            echo "Cambios detectados. Ultimo commit anterior: ${lastCommit}"
                        }
                    } else {
                        echo "Primer despliegue: no existe registro previo de commit."
                    }

                    // Guarda el commit actual para el próximo build
                    writeFile file: commitFile, text: currentCommit
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo "Construyendo imagen Docker PHP..."
                    sh """
                        docker build -f .docker/Dockerfile \
                        --build-arg BUILD_VERSION=${BUILD_VERSION} \
                        -t ${IMAGE_NAME}:${BUILD_VERSION} .
                    """
                }
            }
        }

        stage('Login to DockerHub') {
            steps {
                echo "Iniciando sesión en DockerHub..."
                sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
            }
        }

        stage('Push to DockerHub') {
            steps {
                echo "Subiendo imagen a DockerHub..."
                sh """
                    docker push ${IMAGE_NAME}:${BUILD_VERSION}
                    docker tag ${IMAGE_NAME}:${BUILD_VERSION} ${IMAGE_NAME}:latest
                    docker push ${IMAGE_NAME}:latest
                """
            }
        }
    }

    post {
        always {
            echo "Limpieza final..."
            sh 'docker system prune -f || true'
        }
        success {
            echo "Pipeline completado con éxito."
        }
        failure {
            echo "El pipeline falló."
        }
    }
}
