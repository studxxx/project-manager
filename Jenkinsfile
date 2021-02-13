pipeline {
  agent any
  environment {
    CI = 'true'
  }
  stages {
    stage("Init") {
      steps {
        sh "make init"
      }
    }
    stage("Test") {
      parallel {
        stage("Manager") {
          steps {
            sh "make manager-test"
          }
          post {
            failure {
              archiveArtifacts 'manager/var/log/**/*'
            }
          }
        }
      }
    }
    stage("Down") {
      steps {
        sh "make docker-down-clear"
      }
    }
  }
  post {
    always {
      sh "make docker-down-clear || true"
    }
  }
}
