pipeline {
    agent any

    environment {
        // Credenciales de Docker Hub configuradas en Jenkins
        DOCKERHUB_CREDENTIALS = credentials('docker-hub')
        IMAGE_NAME = "dark093/ultimoparcial"
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
                echo "Instalando dependencias con Composer si existen..."
                sh '''
                    if [ -f composer.json ]; then
                        docker run --rm -v $(pwd):/app -w /app composer install --no-interaction --prefer-dist
                    else
                        echo "No se encontró composer.json, se omite instalación."
                    fi
                '''
            }
        }

        stage('Detectar cambios') {
            steps {
                script {
                    echo "Verificando cambios desde el último despliegue..."

                    def currentCommit = sh(script: "git rev-parse HEAD", returnStdout: true).trim()
                    def commitFile = "${env.WORKSPACE}/.last_commit"

                    if (fileExists(commitFile)) {
                        def lastCommit = readFile(commitFile).trim()
                        if (currentCommit == lastCommit) {
                            echo "No hay cambios nuevos desde el último despliegue (${lastCommit})."
                            currentBuild.result = 'SUCCESS'
                            currentBuild.displayName = "Sin cambios"
                            error("No se detectaron cambios nuevos, se detiene el pipeline.")
                        } else {
                            echo "Cambios detectados. Último commit anterior: ${lastCommit}"
                        }
                    } else {
                        echo "Primer despliegue: no existe registro previo de commit."
                    }

                    writeFile file: commitFile, text: currentCommit
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo "Construyendo imagen Docker PHP-FPM..."
                    sh """
                        docker build -t ${IMAGE_NAME}:${BUILD_VERSION} .
                    """
                }
            }
        }

        stage('Login a Docker Hub') {
            steps {
                echo "Iniciando sesión en Docker Hub..."
                sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
            }
        }

        stage('Push Docker Image') {
            steps {
                echo "Subiendo imagen a Docker Hub..."
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
            echo "Limpieza de recursos de Docker..."
            sh 'docker system prune -f || true'
        }
        success {
            echo "Pipeline completado con éxito. Imagen ${IMAGE_NAME}:${BUILD_VERSION} subida a Docker Hub."
        }
        failure {
            echo "Pipeline falló."
        }
    }
}
