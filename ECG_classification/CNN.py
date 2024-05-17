import torch
from torchvision import datasets, transforms, models
from torch.utils.data import Dataset, DataLoader
import torch.nn as nn
import torch.optim as optim
import pandas as pd
import numpy as np
import os
from PIL import Image
import matplotlib.pyplot as plt
import signals
from scipy.io import loadmat
import seaborn as sns
from sklearn.metrics import confusion_matrix
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score

alexnet = models.alexnet(weights=models.AlexNet_Weights.DEFAULT)

for param in alexnet.parameters():
    param.requires_grad = False

num_classes = 55
alexnet.classifier[6] = nn.Linear(4096, num_classes)


loss_func = nn.BCEWithLogitsLoss()
optimizer = optim.Adam(alexnet.parameters())

# def train(model, loss_criterion, optimizer, train_data_loader, model_name_path, epochs=5):
#     for epoch in range(epochs):
#         model.train()

#         train_loss = 0.0
#         train_acc = 0.0
        
#         for i, (inputs, labels) in enumerate(train_data_loader):
           
#             optimizer.zero_grad()
#             output = model(inputs)
            
#             loss = loss_criterion(output, labels)
#             loss.backward()
#             optimizer.step()
#             train_loss += loss.item() *inputs.size(0)

#             ret, predictions = torch.max(output.data, 1)
#             # correct_counts = predictions.eq(labels.data.view_as(predictions))

#             predictions = (torch.sigmoid(output) > 0.5).float()  # Convert logits to binary predictions
#             correct_counts = (predictions == labels).sum().item()  # Count correct predictions
#             acc = correct_counts / (labels.size(0) * labels.size(1))  # Calculate accuracy

#             train_acc += acc * inputs.size(0)
#             print("Batch number: {:03d}, Training: Loss: {:.4f}, Accuracy: {:.4f}".format(i, loss.item(), acc))
#             # print(loss)
#             torch.save(model.state_dict(), model_name_path)

def train(model, loss_criterion, optimizer, train_data_loader, model_name_path, batches_per_epoch=100, total_batches=50):
    train_losses = []
    train_accuracies = []
    batches = []

    for batch_idx, (inputs, labels) in enumerate(train_data_loader):
        if batch_idx >= total_batches:
            break

        model.train()
        optimizer.zero_grad()
        output = model(inputs)
        
        loss = loss_criterion(output, labels)
        loss.backward()
        optimizer.step()

        train_loss = loss.item()
        predictions = (torch.sigmoid(output) > 0.5).float()
        correct_counts = (predictions == labels).sum().item()
        acc = correct_counts / (labels.size(0) * labels.size(1))

        train_losses.append(train_loss)
        train_accuracies.append(acc)
        batches.append(batch_idx)

        if (batch_idx + 1) % batches_per_epoch == 0 or (batch_idx + 1) == total_batches:
            print("Batch number: {:03d}/{:d}, Training: Loss: {:.4f}, Accuracy: {:.4f}".format(batch_idx + 1, total_batches, train_loss, acc))

    # Plotting the training curves
    plt.figure(figsize=(10, 5), facecolor='lightgrey')
    plt.subplot(1, 2, 1)
    plt.plot(batches, train_losses, label='Training Loss', color='blue', linewidth=2)
    plt.xlabel('Batches')
    plt.ylabel('Loss')
    plt.title('Training Loss')
    plt.grid(1)

    plt.subplot(1, 2, 2)
    plt.plot(batches, train_accuracies, label='Training Accuracy', color='green', linewidth=2)
    plt.xlabel('Batches')
    plt.ylabel('Accuracy')
    plt.title('Training Accuracy')
    plt.grid(1)

    plt.tight_layout()
    plt.show()

    # Save model state after training
    torch.save(model.state_dict(), model_name_path)
    # # Plotting the training curves
    # plt.figure(figsize=(10, 5))
    # plt.subplot(1, 2, 1)
    # plt.plot(range(1, 177), train_losses, label='Training Loss')
    # plt.xlabel('Epochs')
    # plt.ylabel('Loss')
    # plt.title('Training Loss')

    # plt.subplot(1, 2, 2)
    # plt.plot(range(1, 177), train_accuracies, label='Training Accuracy')
    # plt.xlabel('Epochs')
    # plt.ylabel('Accuracy')
    # plt.title('Training Accuracy')

    # plt.tight_layout()
    plt.show()

def get_output(input, model):
    # model = models.alexnet()
    # model.classifier[6] = nn.Linear(4096, num_classes)
    # model.load_state_dict(torch.load('model.pth'))

    for i in range(12):
        signal = input[i]

        signal = signals.butter_lowpass_filter(signal)
        signal -= signals.calc_baseline(signal)

        scalogram = signals.get_scalogram(signal)
        plt.imshow(scalogram, cmap='jet')
        plt.axis('off')
        plt.savefig('temp_scal.jpg', bbox_inches='tight', pad_inches=0)
        plt.close()
        
        scalogram = Image.open('temp_scal.jpg')

        scalogram_tensor = transform(scalogram)
        scalogram_tensor = scalogram_tensor.unsqueeze(0)
        os.remove('temp_scal.jpg')
        output = model(scalogram_tensor)
        
        probabilities = torch.sigmoid(output)
        
        confident_classes = torch.where(probabilities > 0.5)[1]  
        
        class_names = [
    "251147008", "54016002", "164912004", "89792004", "251173003", 
    "164917005", "11157007", "17338001", "251146004", "164890007", 
    "251199005", "426783006", "195060002", "39732003", "164873001", 
    "164889003", "426761007", "713422000", "164942001", "251198002", 
    "59118001", "164937009", "428750005", "233897008", "426995002", 
    "164931005", "164947007", "13640000", "233896004", "27885002", 
    "164909002", "195101003", "233917008", "28189009", "195042002", 
    "164930006", "111975006", "427084000", "251180001", "698252002", 
    "251148003", "270492004", "75532003", "251164006", "426177001", 
    "164865005", "428417006", "47665007", "427393009", "164934002", 
    "446358003", "284470004", "429622005", "74390002", "59931005"
]

        
        print("Clasele cu probabilitatea peste 50%:")
        for class_idx in confident_classes:
            class_name = class_names[class_idx]
            print(class_name)
    
    print(' ')
    print(' ')


class CustomDataset(Dataset):
    def __init__(self, csv_file, root_dir, transform=None):
        self.data_labels = pd.read_csv(csv_file)
        self.root_dir = root_dir
        self.transform = transform

    def __len__(self):
        return len(self.data_labels)

    def __getitem__(self, index):
        img_path = os.path.join(self.root_dir, str(self.data_labels.iloc[index, 0]))
        image = Image.open(img_path)
        y_label = torch.tensor(self.data_labels.iloc[index].values.tolist()[1:], dtype=torch.float32)
    
        if self.transform:
            image = self.transform(image)

        return (image, y_label)

transform = transforms.Compose([
    transforms.CenterCrop((224,224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225])
])

# def test_accuracy(model, test_loader):
#     correct = 0
#     total = 0
#     model.eval()

#     with torch.no_grad():
#         for images, labels in test_loader:
#             outputs = model(images)
#             _, predicted = torch.max(outputs, 1)
#             total += labels.size(0)
#             correct += (predicted == labels).sum().item()

#     accuracy = correct / total
#     return accuracy

def test(model, loss_criterion, test_data_loader):
    model.eval()

    test_loss = 0.0
    test_acc = 0.0
    total_samples = 0

    with torch.no_grad():
        for inputs, labels in test_data_loader:
            output = model(inputs)
            loss = loss_criterion(output, labels)
            test_loss += loss.item() * inputs.size(0)
            total_samples += inputs.size(0)

            # Calculate accuracy
            predictions = (torch.sigmoid(output) > 0.5).float()  # Convert logits to binary predictions
            correct_counts = (predictions == labels).sum().item()  # Count correct predictions
            acc = correct_counts / (labels.size(0) * labels.size(1))  # Calculate accuracy
            test_acc += acc * inputs.size(0)

    # Average loss and accuracy over all samples
    avg_test_loss = test_loss / total_samples
    avg_test_acc = test_acc / total_samples

    print('Acuratete medie:', avg_test_acc)

def get_data_loaders(train_dir,  transform, csv_file, batch_size=32, shuffle=True):
    train_dataset = CustomDataset(csv_file=csv_file, root_dir=train_dir,transform=transform)
    train_loader = DataLoader(train_dataset, batch_size=batch_size, shuffle=shuffle)
   
    return train_loader


train_loader = get_data_loaders('scalograms_dataset_v2', transform, 'labels_v2.csv')
test_loader  = get_data_loaders('scalograms_test', transform, 'test_labels.csv')
# y_true, y_pred = get_predictions(alexnet, train_loader)
# get_metrics(y_pred, y_true)
train(alexnet, loss_func, optimizer, train_loader, 'model.pth', 1)
# test(alexnet,loss_func, test_loader)
data = loadmat('JS35731.mat')['val']
get_output(data, alexnet)

data = loadmat('JS37238.mat')['val']
get_output(data, alexnet)