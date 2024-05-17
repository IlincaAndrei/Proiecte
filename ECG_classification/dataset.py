import pickle
import signals
import pandas as pd
import os
from sklearn.preprocessing import MultiLabelBinarizer
import numpy as np
import sys
from scipy.io import loadmat
from PIL import Image
import matplotlib.pyplot as plt

SNOMED_CT_file_name          = 'ConditionNames_SNOMED-CT.csv'
SNOMED_CT_column_name        = 'Snomed_CT.csv'
dataset_directory_name       = 'ECG_12-lead_dataset/WFDBRecords'
labels_file_name             = 'labels.csv'
scalograms_file_name          = 'scalograms_test'
condition_names_file_name    = 'ConditionNames_SNOMED-CT.csv'
diagnosis_codes_coliumn_name = 'Snomed_CT'
labels_output_file           = 'test_labels.csv'


def extract_CSV_column(file_name, column_name):
    """
        Extracts specified table column.
    """
    data = pd.read_csv(file_name)
    column = data[column_name]
    return column

def organise_labels_in_CSV(dataset_directory_name, condition_names_file_name, diagnosis_codes_column_name):
    diagnosis = []
    file_order = []
    for root, dirs, files in os.walk(dataset_directory_name):
        for file_name in files:
            if(file_name.endswith(".hea")):
                file_order.append(file_name[:-4])
                
                file_path = os.path.join(root, file_name)
                with open(file_path, 'r') as hea_file:
                    for line in hea_file:
                        if line.startswith("#Dx: "):
                            split_line = line.split("#Dx: ")[1]
                            codes = split_line.split(",")
                            codes = [code.strip() for code in codes]
                diagnosis.append(codes)

    diagnosis_codes = pd.read_csv(condition_names_file_name)[diagnosis_codes_column_name]
    diagnosis_codes = list(set(diagnosis_codes))
    diagnosis_codes = [str(_) for _ in diagnosis_codes]

    mlb = MultiLabelBinarizer(classes=diagnosis_codes)
    encoded_labels = mlb.fit_transform(diagnosis)

    encoded_labels_columns = np.vstack([np.array(diagnosis_codes), encoded_labels])

    file_order = np.array(['file_name'] + file_order)
    data_frame = np.insert(encoded_labels_columns, 0, file_order, axis=1)

    df = pd.DataFrame(data_frame)
    df.to_csv('labels.csv', header=False, index=False)

def create_scalograms(ecg_data_directory_name, scalograms_output_directory, labels_file, labels_output_file):
    data_frame = pd.read_csv(labels_file, nrows=1).columns.tolist()
    df = pd.read_csv(labels_file)
    data_frame = np.array([data_frame])
    
    pd.DataFrame(data_frame).to_csv( labels_output_file, header=False, index=False)

    if (not os.path.exists(scalograms_output_directory)):
        os.makedirs(scalograms_output_directory)

    for root, dirs, files in os.walk(ecg_data_directory_name):
        for file_name in files:
            if(file_name.endswith(".mat")):
                file_path = os.path.join(root, file_name)
                data = loadmat(file_path)['val']
                
                for i in range(len(data)):
                    
                    signal = signals.butter_lowpass_filter(data[i])
                    signal -= signals.calc_baseline(signal)
                   
                    scalogram = signals.get_scalogram(signal)
                    plt.imshow(scalogram, cmap='jet')
                    plt.axis('off')
                    image_name = file_name[:-4] + '_' + str(i) 
                    scal_path = scalograms_file_name + '/' + image_name + '.jpg'
                    plt.savefig(scal_path, bbox_inches='tight', pad_inches=0)
                    plt.close()
                    
                    row= df.loc[df['file_name'] == file_name[:-4]].values.tolist()[0]
    
                    data_frame = [image_name + '.jpg']
                    data_frame.extend(row[1:]) 
                    data_frame = np.array([data_frame])
            
                    pd.DataFrame(data_frame).to_csv( labels_output_file, header=False, index=False, mode='a')
                    


def get_labels(file_name):
    labels = []
    with open(file_name, 'r') as file:
        for line in file:
            _line = line.strip()
            values = _line.split(',')
    
            while (values and values[-1] == ''):
                values.pop()

            labels.append(values)
    
    return labels



# organise_labels_in_CSV(dataset_directory_name, condition_names_file_name, diagnosis_codes_coliumn_name)
create_scalograms(dataset_directory_name, scalograms_file_name, labels_file_name, labels_output_file)