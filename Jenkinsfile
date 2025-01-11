pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'main' // Git branch nomi
        dockerImage = '' // Docker image o'zgaruvchisi
    }

    agent any

    stages {
        stage('Git - Checkout') {
            steps {
                echo "Repozitoriyani klonlash..."
                checkout([$class: 'GitSCM', branches: [[name: branchName]], userRemoteConfigs: [[url: gitRepo]]])
            }
        }

        stage('Docker Image Qurish') {
            steps {
                script {
                    echo "Docker image yaratish..."
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/task11:${env.BUILD_NUMBER}") // Build number bilan Docker image yaratish
                    dockerImage.tag("latest") // 'latest' teg qo‘shish
                }
            }
        }

        stage('Docker Image-ni Push qilish') {
            steps {
                script {
                    echo "Docker Hub uchun global credential bilan autentifikatsiya qilish..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin' // Docker Hub login
                    }

                    echo "Docker image-ni Docker Hub-ga yuborish..."
                    dockerImage.push("${env.BUILD_NUMBER}") // Build number bilan image push
                    dockerImage.push("latest") // 'latest' teg bilan image push
                }
            }
        }

        stage('Docker Image-ni Ishga Tushirish') {
            steps {
                script {
                    echo "Docker image-ni ishga tushirish..."
                    // Docker image-ni to'g'ri ishlashini tekshirish uchun ishga tushir
                    sh "docker run -d -p 8001:8000 --name test-container ${env.DOCKER_USERNAME}/task11:${env.BUILD_NUMBER}"
                    // Kerak bo'lsa `-d` bayrog'ini qo'shimcha bayroqlar yoki buyruqlar bilan almashtirishingiz mumkin.
                    echo "Docker image test-container konteynerida ishlamoqda"
                }
            }
        }

        stage('Tozalash') {
            steps {
                script {
                    echo "Docker image-larni tozalash..."
                    sh "docker rmi ${env.DOCKER_USERNAME}/task11:${env.BUILD_NUMBER} || true" // Build image-ni o'chirish
                    sh "docker rmi ${env.DOCKER_USERNAME}/task11:latest || true" // 'latest' image-ni o'chirish
                    sh "docker stop test-container || true" // Test konteynerini to'xtatish
                    sh "docker rm test-container || true" // Test konteynerini o'chirish
                }
            }
        }
    }

    post {
        success {
            echo "Build va push muvaffaqiyatli bajarildi!"
        }
        failure {
            echo "Build muvaffaqiyatsiz tugadi!"
        }
        always {
            echo "Ish joyini tozalash..."
            cleanWs() // Workspace-ni tozalash
        }
    }
}
