pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "shodlik/task1"
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Cloning repository..."
                checkout scm
            }
        }

        stage('Build and Push Docker Image') {
            steps {
                script {
                    echo "Building Docker image..."
                    sh """
                    echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin
                    docker build -t ${DOCKER_IMAGE}:${env.BUILD_NUMBER} .
                    docker tag ${DOCKER_IMAGE}:${env.BUILD_NUMBER} ${DOCKER_IMAGE}:latest
                    docker push ${DOCKER_IMAGE}:${env.BUILD_NUMBER}
                    docker push ${DOCKER_IMAGE}:latest
                    """
                }
            }
        }

        stage('Run Docker Container') {
            steps {
                script {
                    echo "Running container from the latest image..."
                    sh """
                    docker rm -f task1-container || true
                    docker run -d --name task1-container -p 8000:8000 ${DOCKER_IMAGE}:latest
                    """
                }
            }
        }
    }

    post {
        success {
            echo "Build and deployment successful!"
        }
        failure {
            echo "Pipeline failed. Check logs for details."
        }
    }
}
