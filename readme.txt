1. First open the google cloud shell and enable the google cloud api of sqladmin and container 
  > gcloud services enable container.googleapis.com sqladmin.googleapis.com

2. Then set the zone as you want 
  > gcloud config set compute/zone us-central1-c

3. Then set project ID 
  > export PROJECT_ID=project-id

4. Set working directory as an variable
  > WORKING_DIR=$(pwd)

5. Create kubernetes cluster 
  > CLUSTER_NAME=persistent-disk-tutorial
    gcloud container clusters create $CLUSTER_NAME \
    --num-nodes=1 --enable-autoupgrade --no-enable-basic-auth \
    --no-issue-client-certificate --enable-ip-alias --metadata \
    disable-legacy-endpoints=true

6. Create sql instance 
  > INSTANCE_NAME=mysql-instance
    gcloud sql instances create $INSTANCE_NAME

7. Create user on sql and give pass 
  > CLOUD_SQL_PASSWORD=Anurag123
    gcloud sql users create wordpress --host=% --instance $INSTANCE_NAME \
    --password $CLOUD_SQL_PASSWORD

8. Create service account give it permision and add its id in enviroment variable
  > SA_NAME=cloudsql-proxy
    gcloud iam service-accounts create $SA_NAME --display-name $SA_NAME

  > SA_EMAIL=$(gcloud iam service-accounts list \
    --filter=displayName:$SA_NAME \
    --format='value(email)')

  > gcloud projects add-iam-policy-binding $PROJECT_ID \
    --role roles/cloudsql.client \
    --member serviceAccount:$SA_EMAIL

9. Create key for the service account which we created an push that key in the working directory
   > gcloud iam service-accounts keys create $WORKING_DIR/key.json \
    --iam-account $SA_EMAIL

10. Create secrets on kubernetes of username password and the key.json file of service account

  > kubectl create secret generic cloudsql-db-credentials \
    --from-literal username=wordpress \
    --from-literal password=$CLOUD_SQL_PASSWORD

  > kubectl create secret generic cloudsql-instance-credentials \
    --from-file $WORKING_DIR/key.json
11. Change the instance connection name in php-deployement.yaml as mention on the cloud sql insatance dashboard

12. then juz apply the yaml file on kubernetes 
  > kubectl apply -f php-deployement.yaml 

13. run command kubectl get service u will get the exterrnal ip of teh app and you can see the ouptup