pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Git repozitoriyasini olish
                checkout scm
            }
        }


    
        stage('Build Docker Image') {
            steps {
                // Docker imidj yaratish
                sh 'docker build -t task1 .'
            }
        }

        stage('Push to Docker Hub') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
                        sh '''
                            docker login -u $DOCKER_USERNAME -p $DOCKER_PASSWORD
                            docker tag task1 shodlik/task1
                            docker push shodlik/task1
                            docker logout
                        '''
                    }
                }
            }
        }
    }

    post {
        always {
            // Build tugagandan keyin kerakli jarayonlarni bajarish
            echo 'Build Finished'
        }
        success {
            echo 'Build Succeeded!'
        }
        failure {
            echo 'Build Failed!'
        }
    }
}
