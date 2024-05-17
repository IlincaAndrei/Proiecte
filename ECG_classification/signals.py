import numpy as np
import pywt
from PIL import Image
from scipy.signal import butter, filtfilt
from scipy.io import loadmat

########## calc_baseline function: https://github.com/spebern/py-bwr/blob/master/bwr.py ##################
def calc_baseline(signal):
    """
    Calculate the baseline of signal.

    Args:
        signal (numpy 1d array): signal whose baseline should be calculated


    Returns:
        baseline (numpy 1d array with same size as signal): baseline of the signal
    """
    ssds = np.zeros((3))

    cur_lp = np.copy(signal)
    iterations = 0
    while True:
        # Decompose 1 level
        lp, hp = pywt.dwt(cur_lp, "db4")

        # Shift and calculate the energy of detail/high pass coefficient
        ssds = np.concatenate(([np.sum(hp ** 2)], ssds[:-1]))

        # Check if we are in the local minimum of energy function of high-pass signal
        if ssds[2] > ssds[1] and ssds[1] < ssds[0]:
            break

        cur_lp = lp[:]
        iterations += 1

    # Reconstruct the baseline from this level low pass signal up to the original length
    baseline = cur_lp[:]
    for _ in range(iterations):
        baseline = pywt.idwt(baseline, np.zeros((len(baseline))), "db4")

    return baseline[: len(signal)]

def butter_lowpass(cutoff, sf, order=5):
    nyquist = 0.5 * sf
    normal_cutoff = cutoff / nyquist
    b, a = butter(order, normal_cutoff, btype='low', analog=False)
    return b, a

def butter_lowpass_filter(signal, cutoff=20, sf=500, order=5):
    """
        Uses SciPy butter to do a butter low pass on an input signal.

        Args:
            SciPy format arguments.
            sf- sample frequency 
            
        Returns:
            Filtered signal.
    """
    b, a = butter_lowpass(cutoff, sf, order=order)
    y = filtfilt(b, a, signal)
    return y 

def get_scalogram(signal, scales = np.arange(1, 128), wavelet = 'morl', size=(254,254)):
    """
        Returns the scalogram of a signal as a numpy array.

        Args:
            pywt format arguments.
            size - set None for any size.

        Returns:
            scalogram numpy array.
    """
    cofs, freqs = pywt.cwt(signal, scales, wavelet)
    scalogram = np.abs(cofs)
    
    if(size is not None):
        scalogram = Image.fromarray(scalogram).resize(size, Image.BICUBIC)
    
    scalogram_np = np.array(scalogram)

    return scalogram_np


# import matplotlib.pyplot as plt
# signal = loadmat('ECG_12-lead_dataset/WFDBRecords/06/068/JS06148.mat')['val'][0]
# time = np.arange(0, len(signal) * 0.002, 0.002)


# signal = butter_lowpass_filter(signal)
# signal = signal - calc_baseline(signal)
# plt.plot(time, signal)
# plt.show()

