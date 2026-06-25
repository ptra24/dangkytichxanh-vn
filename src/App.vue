<script setup>
import { ref, watch, nextTick, onMounted, computed } from 'vue';
import { 
  Check, 
  X, 
  AlertCircle, 
  Send, 
  CheckCircle2,
  MessageSquare,
  Users,
  Download,
  LogOut,
  Trash2,
  Edit,
  Phone,
  Shield,
  Plus,
  ExternalLink,
  BookOpen,
  Globe
} from '@lucide/vue';
import Header from './components/Header.vue';
import HeroSection from './components/HeroSection.vue';
import StatsBar from './components/StatsBar.vue';
import WhyUs from './components/WhyUs.vue';
import ProcessSteps from './components/ProcessSteps.vue';
import Testimonials from './components/Testimonials.vue';
import Footer from './components/Footer.vue';
import { services } from './data/services';
import { articles as staticArticles } from './data/articles';
import ArticleView from './components/ArticleView.vue';

const articles = ref([...staticArticles]);
const currentServiceIndex = ref(0);
const billingCycle = ref('monthly');
const isModalOpen = ref(false);
const selectedArticle = ref(null);
const selectedPage = ref(null); // 'blog' | 'gioi-thieu' | 'legal-terms' | 'legal-privacy' | 'legal-dmca' | 'admin'
const enableBlog = ref(typeof window !== 'undefined' ? window.ENABLE_BLOG !== false : true);

// Modal chi tiết khách hàng đăng ký
const isLeadDetailModalOpen = ref(false);
const selectedLeadDetail = ref(null);
const openLeadDetail = (lead) => {
  selectedLeadDetail.value = lead;
  isLeadDetailModalOpen.value = true;
};
const closeLeadDetail = () => {
  isLeadDetailModalOpen.value = false;
  selectedLeadDetail.value = null;
};

// ── SEO HELPERS ────────────────────────────────────────────────────────────
const SITE = 'Đăng Ký Tích Xanh';
const DEFAULT_DESC = 'Đăng ký tích xanh Facebook, Instagram 15 phút. Không cần báo chí, làm xong thanh toán. 100% thành công, 2.368 khách. Hotline 0968.825.068.';

const setMeta = (name, content, attr = 'name') => {
  let el = document.querySelector(`meta[${attr}="${name}"]`);
  if (!el) { el = document.createElement('meta'); el.setAttribute(attr, name); document.head.appendChild(el); }
  el.setAttribute('content', content);
};

const setLink = (rel, href) => {
  let el = document.querySelector(`link[rel="${rel}"]`);
  if (!el) { el = document.createElement('link'); el.setAttribute('rel', rel); document.head.appendChild(el); }
  el.setAttribute('href', href);
};

const updateMeta = (title, description) => {
  const fullTitle = title ? `${title} | ${SITE}` : `Đăng Ký Tích Xanh Fanpage, Instagram 15 Phút | ${SITE}`;
  const desc = description || DEFAULT_DESC;
  document.title = fullTitle;
  setMeta('description', desc);
  setMeta('og:title', fullTitle, 'property');
  setMeta('og:description', desc, 'property');
  setMeta('og:url', window.location.href, 'property');
  setMeta('og:type', 'website', 'property');
  setLink('canonical', window.location.href);
};
// ───────────────────────────────────────────────────────────────────────────

const selectService = (index) => {
  currentServiceIndex.value = index;
  selectedArticle.value = null;
  selectedPage.value = null;
  const service = services[index];
  const urlMap = {
    0: '/dang-ky-tich-xanh-fanpage',
    1: '/dang-ky-tich-xanh-ca-nhan',
    2: '/dang-ky-tich-xanh-instagram',
    3: '/dang-ky-tich-xanh-tiktok'
  };
  const url = urlMap[index] || '/';
  window.history.pushState({}, '', url);
  
  const metaMap = {
    0: ['Đăng Ký Tích Xanh Fanpage 15 Phút | 1.5 Triệu', 'Đăng ký tích xanh Fanpage chỉ 15 phút, 1.5 triệu trọn gói. Tên Fanpage nào cũng được. Làm xong thanh toán. Hotline 0968.825.068.'],
    1: ['Đăng Ký Tích Xanh Facebook Cá Nhân | 1.5 Triệu', 'Hỗ trợ cá nhân, KOL đăng ký tích xanh Facebook. Tư vấn miễn phí, không yêu cầu mật khẩu. Hotline 0968.825.068.'],
    2: ['Đăng Ký Tích Xanh Instagram – 15 Phút, 1.5 Triệu', 'Tích xanh Instagram chỉ 15 phút, 1.5 triệu trọn gói. Không cần báo chí, 99% thành công. Gọi 0968.825.068 tư vấn miễn phí.'],
    3: ['Đăng Ký Tích Xanh TikTok | Tư Vấn Miễn Phí', 'Tư vấn và hỗ trợ đăng ký tích xanh TikTok cho creator, thương hiệu, shop. Báo phí trước khi làm. Hotline 0968.825.068.']
  };
  const [t, d] = metaMap[index] || [];
  updateMeta(t, d);
};

const selectArticle = (article) => {
  if (enableBlog.value === false) {
    window.history.pushState({}, '', '/');
    updateMeta();
    return;
  }
  selectedArticle.value = article;
  selectedPage.value = null;
  window.history.pushState({}, '', `/tin-tuc/${article.id}`);
  updateMeta(article.title, article.description);
};

const clearArticle = () => {
  selectedArticle.value = null;
  window.history.pushState({}, '', '/');
  updateMeta();
};

const openPage = (page) => {
  if (page === 'blog' && enableBlog.value === false) {
    window.history.pushState({}, '', '/');
    updateMeta();
    return;
  }
  selectedArticle.value = null;
  selectedPage.value = page;
  const urlMap = { blog: '/blog', 'gioi-thieu': '/gioi-thieu', 'legal-terms': '/legal/terms', 'legal-privacy': '/legal/privacy', 'legal-dmca': '/legal/dmca', 'admin': '/admin' };
  const metaMap = {
    blog: ['Tin Tức & Kiến Thức Tích Xanh 2026', 'Kiến thức, hướng dẫn và tin tức mới nhất về Đăng ký tích xanh Facebook, Instagram, TikTok, WhatsApp.'],
    'gioi-thieu': ['Giới Thiệu AZ Media', 'Đội ngũ Đăng Ký Tích Xanh với 5+ năm kinh nghiệm Meta Verified — đã hỗ trợ 2.368+ khách hàng. Cam kết làm xong mới thanh toán.'],
    'legal-terms': ['Điều Khoản Dịch Vụ', DEFAULT_DESC],
    'legal-privacy': ['Chính Sách Bảo Mật', DEFAULT_DESC],
    'legal-dmca': ['DMCA', DEFAULT_DESC],
    'admin': ['Admin Dashboard - Quản lý đăng ký', DEFAULT_DESC],
  };
  window.history.pushState({}, '', urlMap[page] || '/');
  const [t, d] = metaMap[page] || [];
  updateMeta(t, d);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const closePage = () => {
  selectedPage.value = null;
  window.history.pushState({}, '', '/');
  updateMeta();
};

const handleNavigateSection = async (sectionId) => {
  selectedArticle.value = null;
  selectedPage.value = null;
  window.history.pushState({}, '', '/');
  updateMeta();
  await nextTick();
  if (sectionId === 'top') {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } else {
    const el = document.getElementById(sectionId);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' });
    }
  }
};

// ── ADMIN PANEL LOGIC & EXTENSIONS ──────────────────────────────────────────
// Khôi phục session: chỉ chấp nhận nếu role hợp lệ (admin | employee)
const _savedRole = sessionStorage.getItem('admin_role') || '';
const _validRoles = ['admin', 'employee'];
const adminPasscode = ref(_validRoles.includes(_savedRole) ? (sessionStorage.getItem('admin_passcode') || '') : '');
const adminRole = ref(_validRoles.includes(_savedRole) ? _savedRole : '');
const adminName = ref(_validRoles.includes(_savedRole) ? (sessionStorage.getItem('admin_name') || '') : '');
// Nếu session lưu không hợp lệ, xóa sạch
if (!_validRoles.includes(_savedRole)) {
  sessionStorage.removeItem('admin_passcode');
  sessionStorage.removeItem('admin_role');
  sessionStorage.removeItem('admin_name');
}
const activeAdminTab = ref('leads'); // 'leads' | 'employees' | 'chat'
const adminError = ref('');
const leads = ref([]);
const leadsLoading = ref(false);
// isAdminAuthenticated: gate chính - phải có CẢ passcode VÀ role hợp lệ
const isAdminAuthenticated = computed(() => !!adminPasscode.value && _validRoles.includes(adminRole.value));
const realtimeEnabled = ref(true);
let pollingInterval = null;

// Quản lý Nhân viên State
const employeesList = ref([]);
const employeesLoading = ref(false);
const showEmployeeModal = ref(false);
const employeeForm = ref({ id: null, name: '', passcode: '', status: 'active' });
const employeeErrors = ref({});

// Quản lý Bài viết & SEO State
const rawBlogPosts = ref([]);
const blogLoading = ref(false);
const showBlogModal = ref(false);
const blogForm = ref({
  id: null,
  title: '',
  excerpt: '',
  content: '',
  image_url: '',
  meta_title: '',
  meta_description: '',
  meta_keywords: ''
});
const blogErrors = ref({});

const loginUsername = ref('');
const loginPassword = ref('');

const verifyPasscode = async () => {
  if (!loginUsername.value.trim() || !loginPassword.value.trim()) {
    adminError.value = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu';
    return;
  }
  try {
    const res = await fetch('/api/admin/verify-passcode', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: loginUsername.value,
        password: loginPassword.value
      })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      adminPasscode.value = data.passcode;
      sessionStorage.setItem('admin_passcode', data.passcode);
      sessionStorage.setItem('admin_role', data.role);
      sessionStorage.setItem('admin_name', data.name);
      adminRole.value = data.role;
      adminName.value = data.name;
      adminError.value = '';
      activeAdminTab.value = 'leads';
      fetchLeads();
      startPolling();
    } else {
      adminError.value = data.message || 'Tên đăng nhập hoặc mật khẩu không chính xác';
    }
  } catch (err) {
    adminError.value = 'Không thể kết nối đến máy chủ';
  }
};

const fetchLeads = async () => {
  if (!adminPasscode.value) return;
  leadsLoading.value = true;
  try {
    const res = await fetch('/api/admin/contacts', {
      headers: {
        'X-Admin-Passcode': adminPasscode.value
      }
    });
    if (res.status === 401) {
      logoutAdmin();
      adminError.value = 'Phiên làm việc hết hạn. Vui lòng nhập lại mật mã.';
      return;
    }
    const data = await res.json();
    if (data.success) {
      leads.value = data.data;
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách đăng ký:', err);
  } finally {
    leadsLoading.value = false;
  }
};

const updateLeadStatus = async (id, status) => {
  try {
    const res = await fetch(`/api/admin/contacts/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-Admin-Passcode': adminPasscode.value
      },
      body: JSON.stringify({ status })
    });
    const data = await res.json();
    if (data.success) {
      const lead = leads.value.find(l => l.id === id);
      if (lead) lead.status = status;
    }
  } catch (err) {
    console.error('Lỗi khi cập nhật trạng thái:', err);
  }
};

const deleteLead = async (id) => {
  if (!confirm('Bạn có chắc chắn muốn xóa lượt đăng ký này?')) return;
  try {
    const res = await fetch(`/api/admin/contacts/${id}`, {
      method: 'DELETE',
      headers: {
        'X-Admin-Passcode': adminPasscode.value
      }
    });
    const data = await res.json();
    if (data.success) {
      leads.value = leads.value.filter(l => l.id !== id);
    } else {
      alert(data.message || 'Không thể xóa lượt đăng ký.');
    }
  } catch (err) {
    console.error('Lỗi khi xóa lượt đăng ký:', err);
  }
};

const startPolling = () => {
  stopPolling();
  if (realtimeEnabled.value && selectedPage.value === 'admin' && activeAdminTab.value === 'leads') {
    pollingInterval = setInterval(fetchLeads, 4000);
  }
};

const stopPolling = () => {
  if (pollingInterval) {
    clearInterval(pollingInterval);
    pollingInterval = null;
  }
};

const logoutAdmin = () => {
  sessionStorage.removeItem('admin_passcode');
  sessionStorage.removeItem('admin_role');
  sessionStorage.removeItem('admin_name');
  adminPasscode.value = '';
  adminRole.value = '';
  adminName.value = '';
  loginUsername.value = '';
  loginPassword.value = '';
  leads.value = [];
  stopPolling();
  stopChatPolling();
  closePage();
};

const fetchEmployees = async () => {
  if (!adminPasscode.value || adminRole.value !== 'admin') return;
  employeesLoading.value = true;
  try {
    const res = await fetch('/api/admin/employees', {
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) {
      employeesList.value = data.data;
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách nhân viên:', err);
  } finally {
    employeesLoading.value = false;
  }
};

const openAddEmployeeModal = () => {
  employeeForm.value = { id: null, name: '', passcode: '', status: 'active' };
  employeeErrors.value = {};
  showEmployeeModal.value = true;
};

const openEditEmployeeModal = (emp) => {
  employeeForm.value = { ...emp };
  employeeErrors.value = {};
  showEmployeeModal.value = true;
};

const saveEmployee = async () => {
  employeeErrors.value = {};
  if (!employeeForm.value.name.trim()) {
    employeeErrors.value.name = 'Vui lòng nhập tên nhân viên.';
  }
  if (!employeeForm.value.passcode.trim()) {
    employeeErrors.value.passcode = 'Vui lòng nhập mật mã.';
  }
  if (Object.keys(employeeErrors.value).length > 0) return;

  const isEdit = employeeForm.value.id !== null;
  const url = isEdit ? `/api/admin/employees/${employeeForm.value.id}` : '/api/admin/employees';
  const method = isEdit ? 'PUT' : 'POST';

  try {
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-Admin-Passcode': adminPasscode.value
      },
      body: JSON.stringify(employeeForm.value)
    });
    const data = await res.json();
    if (data.success) {
      showEmployeeModal.value = false;
      fetchEmployees();
    } else {
      if (data.errors) {
        employeeErrors.value = data.errors;
      } else {
        alert(data.message || 'Có lỗi xảy ra.');
      }
    }
  } catch (err) {
    console.error('Lỗi khi lưu nhân viên:', err);
  }
};

const deleteEmployee = async (id) => {
  if (!confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) return;
  try {
    const res = await fetch(`/api/admin/employees/${id}`, {
      method: 'DELETE',
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) {
      fetchEmployees();
    }
  } catch (err) {
    console.error('Lỗi khi xóa nhân viên:', err);
  }
};

// Quản lý Bài viết & SEO Actions
const fetchRawBlogPosts = async () => {
  if (!adminPasscode.value || adminRole.value !== 'admin') return;
  blogLoading.value = true;
  try {
    const res = await fetch('/api/blog');
    const data = await res.json();
    if (data.success) {
      rawBlogPosts.value = data.data;
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách bài viết:', err);
  } finally {
    blogLoading.value = false;
  }
};

const openAddBlogModal = () => {
  blogForm.value = {
    id: null,
    title: '',
    excerpt: '',
    content: '',
    image_url: '',
    meta_title: '',
    meta_description: '',
    meta_keywords: ''
  };
  blogErrors.value = {};
  showBlogModal.value = true;
};

const openEditBlogModal = (post) => {
  blogForm.value = { ...post };
  blogErrors.value = {};
  showBlogModal.value = true;
};

const saveBlogPost = async () => {
  blogErrors.value = {};
  if (!blogForm.value.title || !blogForm.value.title.trim()) {
    blogErrors.value.title = 'Vui lòng nhập tiêu đề.';
  }
  if (!blogForm.value.content || !blogForm.value.content.trim()) {
    blogErrors.value.content = 'Vui lòng nhập nội dung.';
  }
  if (Object.keys(blogErrors.value).length > 0) return;

  const isEdit = blogForm.value.id !== null;
  const url = isEdit ? `/api/admin/blog/${blogForm.value.id}` : '/api/admin/blog';
  const method = isEdit ? 'PUT' : 'POST';

  try {
    const res = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-Admin-Passcode': adminPasscode.value
      },
      body: JSON.stringify(blogForm.value)
    });
    const data = await res.json();
    if (data.success) {
      showBlogModal.value = false;
      fetchRawBlogPosts();
      fetchBlogPosts();
    } else {
      if (data.errors) {
        blogErrors.value = data.errors;
      } else {
        alert(data.message || 'Có lỗi xảy ra.');
      }
    }
  } catch (err) {
    console.error('Lỗi khi lưu bài viết:', err);
  }
};

const deleteBlogPost = async (id) => {
  if (!confirm('Bạn có chắc chắn muốn xóa bài viết này?')) return;
  try {
    const res = await fetch(`/api/admin/blog/${id}`, {
      method: 'DELETE',
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) {
      fetchRawBlogPosts();
      fetchBlogPosts();
    }
  } catch (err) {
    console.error('Lỗi khi xóa bài viết:', err);
  }
};

// Quản lý Chat (Phía Admin/Employee)
const chatSessions = ref([]);
const activeSessionId = ref(null);
const activeSessionMessages = ref([]);
const adminChatInput = ref('');
const chatSessionsLoading = ref(false);
let chatPollingInterval = null;

const fetchChatSessions = async () => {
  if (!adminPasscode.value) return;
  chatSessionsLoading.value = true;
  try {
    const res = await fetch('/api/admin/chats', {
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) {
      chatSessions.value = data.data;
    }
  } catch (err) {
    console.error('Lỗi tải danh sách chat:', err);
  } finally {
    chatSessionsLoading.value = false;
  }
};

const selectChatSession = async (sessionId) => {
  activeSessionId.value = sessionId;
  await fetchMessagesForActiveSession();
  
  // Đánh dấu đã đọc
  try {
    await fetch(`/api/admin/chats/${sessionId}/read`, {
      method: 'POST',
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    // Tải lại danh sách chat không hiện loading để reset badge chưa đọc
    const res = await fetch('/api/admin/chats', {
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) chatSessions.value = data.data;
  } catch (err) {
    console.error('Lỗi khi đánh dấu đã đọc:', err);
  }
};

const fetchMessagesForActiveSession = async () => {
  if (!activeSessionId.value || !adminPasscode.value) return;
  try {
    const res = await fetch(`/api/admin/chats/${activeSessionId.value}/messages`, {
      headers: { 'X-Admin-Passcode': adminPasscode.value }
    });
    const data = await res.json();
    if (data.success) {
      activeSessionMessages.value = data.data;
      scrollToChatBottom('admin-chat-messages');
    }
  } catch (err) {
    console.error('Lỗi tải tin nhắn của phiên:', err);
  }
};

const sendAdminMessage = async () => {
  if (!adminChatInput.value.trim() || !activeSessionId.value) return;
  const msgText = adminChatInput.value;
  adminChatInput.value = '';
  try {
    const res = await fetch(`/api/admin/chats/${activeSessionId.value}/messages`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Admin-Passcode': adminPasscode.value
      },
      body: JSON.stringify({ message: msgText })
    });
    const data = await res.json();
    if (data.success) {
      activeSessionMessages.value.push(data.data);
      scrollToChatBottom('admin-chat-messages');
      fetchChatSessions(); // Cập nhật lại xem tin nhắn mới nhất ở sidebar
    }
  } catch (err) {
    console.error('Lỗi gửi tin nhắn của admin:', err);
  }
};

const startChatPolling = () => {
  stopChatPolling();
  if (selectedPage.value === 'admin' && adminPasscode.value && activeAdminTab.value === 'chat') {
    chatPollingInterval = setInterval(() => {
      fetchChatSessions();
      if (activeSessionId.value) {
        fetchMessagesForActiveSession();
      }
    }, 3000);
  }
};

const stopChatPolling = () => {
  if (chatPollingInterval) {
    clearInterval(chatPollingInterval);
    chatPollingInterval = null;
  }
};

// Khách hàng Chat Widget State
const isCustomerChatOpen = ref(false);
const customerNameInput = ref('');
const customerNameEntered = ref(localStorage.getItem('chat_customer_name') || '');
const customerSessionId = ref(localStorage.getItem('chat_session_id') || '');
const customerChatMessages = ref([]);
const customerChatInput = ref('');
let customerChatPollingInterval = null;

const isFirstCustomerFetch = ref(true);
const unreadCustomerMessagesCount = ref(0);

const initializeCustomerSession = () => {
  if (!customerSessionId.value) {
    const randId = 'user_' + Math.random().toString(36).substr(2, 9);
    customerSessionId.value = randId;
    localStorage.setItem('chat_session_id', randId);
  }
};

const toggleCustomerChat = () => {
  isCustomerChatOpen.value = !isCustomerChatOpen.value;
  if (isCustomerChatOpen.value) {
    unreadCustomerMessagesCount.value = 0;
    fetchCustomerChatMessages();
    startCustomerChatPolling();
  } else {
    stopCustomerChatPolling();
  }
};

const enterCustomerName = () => {
  if (!customerNameInput.value.trim()) return;
  const nameVal = customerNameInput.value.trim();
  customerNameEntered.value = nameVal;
  localStorage.setItem('chat_customer_name', nameVal);
};

const fetchCustomerChatMessages = async () => {
  if (!customerSessionId.value) return;
  try {
    const res = await fetch(`/api/chat/messages?session_id=${customerSessionId.value}`);
    const data = await res.json();
    if (data.success) {
      const oldLength = customerChatMessages.value.length;
      customerChatMessages.value = data.data;
      scrollToChatBottom('customer-chat-messages');

      if (isFirstCustomerFetch.value) {
        isFirstCustomerFetch.value = false;
        unreadCustomerMessagesCount.value = 0;
      } else if (!isCustomerChatOpen.value) {
        const newMessages = data.data.slice(oldLength);
        const adminNewCount = newMessages.filter(msg => msg.sender_type === 'employee' || msg.sender_type === 'admin').length;
        unreadCustomerMessagesCount.value += adminNewCount;
      }
    }
  } catch (err) {
    console.error('Lỗi tải tin nhắn của khách:', err);
  }
};

const sendCustomerMessage = async () => {
  if (!customerChatInput.value.trim()) return;
  const msgText = customerChatInput.value;
  customerChatInput.value = '';
  initializeCustomerSession();

  const payload = {
    session_id: customerSessionId.value,
    customer_name: customerNameEntered.value || 'Khách vãng lai',
    message: msgText
  };

  try {
    const res = await fetch('/api/chat/messages', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.success) {
      customerChatMessages.value.push(data.data);
      scrollToChatBottom('customer-chat-messages');
    }
  } catch (err) {
    console.error('Lỗi gửi tin nhắn của khách:', err);
  }
};

const startCustomerChatPolling = () => {
  stopCustomerChatPolling();
  customerChatPollingInterval = setInterval(fetchCustomerChatMessages, 3000);
};

const stopCustomerChatPolling = () => {
  if (customerChatPollingInterval) {
    clearInterval(customerChatPollingInterval);
    customerChatPollingInterval = null;
  }
};

const scrollToChatBottom = (containerId) => {
  nextTick(() => {
    const el = document.getElementById(containerId);
    if (el) {
      el.scrollTop = el.scrollHeight;
    }
  });
};

// Social Proof Toast State & Loop
const showSocialProof = ref(false);
const socialProofData = ref(null);
const mockSocialProofs = [
  { name: 'Anh Hoàng', location: 'Hà Nội', service: 'Tích Xanh Fanpage', time: '2 phút trước' },
  { name: 'Chị Mai', location: 'TP. Hồ Chí Minh', service: 'Tích Xanh Instagram', time: '5 phút trước' },
  { name: 'Anh Tuấn', location: 'Đà Nẵng', service: 'Combo Fanpage + IG', time: '12 phút trước' },
  { name: 'Chị Lan', location: 'Cần Thơ', service: 'Tích Xanh Fanpage', time: '15 phút trước' },
  { name: 'Anh Minh', location: 'Hải Phòng', service: 'Tích Xanh TikTok', time: '18 phút trước' },
  { name: 'Chị Vy', location: 'Bình Dương', service: 'Tích Xanh Fanpage', time: '25 phút trước' },
  { name: 'Anh Dũng', location: 'Nha Trang', service: 'Tích Xanh Instagram', time: '30 phút trước' },
];

const publicSocialProofs = ref([]);

const fetchPublicSocialProofs = async () => {
  try {
    const res = await fetch('/api/contacts/recent');
    const data = await res.json();
    if (data.success && data.data) {
      publicSocialProofs.value = data.data.map(item => {
        const createdTime = new Date(item.created_at);
        const diffMins = Math.max(1, Math.round((new Date() - createdTime) / 60000));
        let timeStr = `${diffMins} phút trước`;
        if (diffMins >= 60) {
          const diffHours = Math.floor(diffMins / 60);
          timeStr = `${diffHours} giờ trước`;
          if (diffHours >= 24) {
            timeStr = `${Math.floor(diffHours / 24)} ngày trước`;
          }
        }
        return {
          name: item.name,
          location: 'Việt Nam',
          service: item.service || 'Tư vấn tích xanh',
          time: timeStr
        };
      });
    }
  } catch (err) {
    console.error('Lỗi khi tải social proof từ API:', err);
  }
};

const startSocialProofLoop = () => {
  const trigger = () => {
    let source = [...publicSocialProofs.value];
    
    if (leads.value && leads.value.length > 0) {
      const adminSource = leads.value.slice(0, 10).map(lead => {
        const nameParts = lead.name.split(' ');
        const lastPart = nameParts.pop();
        const anonName = nameParts.length > 0 
          ? nameParts.join(' ') + ' ' + lastPart[0] + '***'
          : lastPart[0] + '***';
        
        const createdTime = new Date(lead.created_at);
        const diffMins = Math.max(1, Math.round((new Date() - createdTime) / 60000));
        let timeStr = `${diffMins} phút trước`;
        if (diffMins >= 60) {
          const diffHours = Math.floor(diffMins / 60);
          timeStr = `${diffHours} giờ trước`;
          if (diffHours >= 24) {
            timeStr = `${Math.floor(diffHours / 24)} ngày trước`;
          }
        }

        return {
          name: anonName,
          location: 'Việt Nam',
          service: lead.service || 'Tư vấn tích xanh',
          time: timeStr
        };
      });
      source = [...source, ...adminSource];
    }

    const finalPool = source.length > 0 ? source : mockSocialProofs;
    const picked = finalPool[Math.floor(Math.random() * finalPool.length)];
    socialProofData.value = picked;
    showSocialProof.value = true;

    setTimeout(() => {
      showSocialProof.value = false;
    }, 6000);

    const nextDelay = 12000 + Math.random() * 6000; // 12-18 seconds interval
    setTimeout(trigger, nextDelay);
  };

  setTimeout(trigger, 2000); // 2 seconds initial delay
};

// Xuất Excel CSV
const exportLeadsToExcel = () => {
  if (leads.value.length === 0) {
    alert('Không có dữ liệu để xuất.');
    return;
  }
  
  const headers = ['ID', 'Họ tên', 'Số điện thoại', 'Link tài khoản / Fanpage', 'Email', 'Dịch vụ đăng ký', 'Lời nhắn', 'Trạng thái liên hệ', 'Ngày đăng ký'];
  
  const rows = leads.value.map(lead => {
    let statusText = 'Chờ xử lý (Pending)';
    if (lead.status === 'completed') statusText = 'Đã hoàn thành';
    else if (lead.status === 'contacting' || lead.status === 'contacted' || lead.status === 'contacted') statusText = 'Đang liên hệ';

    return [
      lead.id,
      `"${lead.name.replace(/"/g, '""')}"`,
      `"${lead.phone}"`,
      `"${lead.link.replace(/"/g, '""')}"`,
      `"${(lead.email || '').replace(/"/g, '""')}"`,
      `"${(lead.service || '').replace(/"/g, '""')}"`,
      `"${(lead.message || '').replace(/"/g, '""')}"`,
      `"${statusText}"`,
      `"${lead.created_at}"`
    ];
  });
  
  const csvContent = [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
  const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  
  const dateStr = new Date().toISOString().slice(0, 10);
  link.setAttribute('download', `DangKyTichXanh_KhachHang_${dateStr}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

watch(realtimeEnabled, (val) => {
  if (val) startPolling();
  else stopPolling();
});

watch(activeAdminTab, (newTab) => {
  stopPolling();
  stopChatPolling();
  if (selectedPage.value === 'admin' && isAdminAuthenticated.value) {
    if (newTab === 'leads') {
      fetchLeads();
      startPolling();
    } else if (newTab === 'chat') {
      fetchChatSessions();
      startChatPolling();
    } else if (newTab === 'employees') {
      fetchEmployees();
    } else if (newTab === 'blog') {
      fetchRawBlogPosts();
    }
  }
});

watch(selectedPage, (newPage) => {
  if (newPage === 'admin') {
    if (isAdminAuthenticated.value) {
      if (activeAdminTab.value === 'leads') {
        fetchLeads();
        startPolling();
      } else if (activeAdminTab.value === 'chat') {
        fetchChatSessions();
        startChatPolling();
      } else if (activeAdminTab.value === 'employees') {
        fetchEmployees();
      } else if (activeAdminTab.value === 'blog') {
        fetchRawBlogPosts();
      }
    }
  } else {
    stopPolling();
    stopChatPolling();
  }
});
const fetchBlogPosts = async () => {
  try {
    const res = await fetch('/api/blog');
    const data = await res.json();
    if (data.success && data.data && data.data.length > 0) {
      // Map API blog posts to the frontend Article schema
      articles.value = data.data.map(post => ({
        id: post.slug, // map slug to id for routing
        title: post.title,
        description: post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 150) + '...',
        category: 'Kiến thức',
        date: new Date(post.created_at).toLocaleDateString('vi-VN'),
        author: 'Admin AZ Media',
        readTime: Math.max(3, Math.ceil(post.content.replace(/<[^>]*>/g, '').length / 500)) + ' phút đọc',
        banner: post.image_url || 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80',
        content: post.content
      }));
      
      // If we are currently on an article page, check if the loaded articles have it
      const path = window.location.pathname;
      if (path.startsWith('/tin-tuc/')) {
        const id = path.split('/').pop();
        const art = articles.value.find(a => a.id === id);
        if (art) {
          selectArticle(art);
        }
      }
    }
  } catch (err) {
    console.error('Lỗi khi tải bài viết từ API:', err);
  }
};

// ───────────────────────────────────────────────────────────────────────────

onMounted(() => {
  // Tải danh sách bài viết từ API
  fetchBlogPosts();

  // Khởi tạo Chat Session cho khách hàng
  initializeCustomerSession();
  if (customerSessionId.value) {
    fetchCustomerChatMessages();
    // Bắt đầu kiểm tra tin nhắn nền (mỗi 10 giây)
    setInterval(() => {
      if (!isCustomerChatOpen.value) {
        fetchCustomerChatMessages();
      }
    }, 10000);
  }

  // Tải thông tin đăng ký gần đây để làm social proof
  fetchPublicSocialProofs();

  // Bắt đầu vòng lặp thông báo nổi (Social Proof)
  startSocialProofLoop();

  const path = window.location.pathname;
  
  if (enableBlog.value === false && (path === '/blog' || path.startsWith('/tin-tuc/'))) {
    window.history.replaceState({}, '', '/');
    updateMeta();
    return;
  }
  
  const serviceUrlMap = {
    '/dang-ky-tich-xanh-fanpage': 0,
    '/dang-ky-tich-xanh-ca-nhan': 1,
    '/dang-ky-tich-xanh-instagram': 2,
    '/dang-ky-tich-xanh-tiktok': 3
  };
  if (serviceUrlMap[path] !== undefined) {
    selectService(serviceUrlMap[path]);
    return;
  }
  
  if (path.startsWith('/tin-tuc/')) {
    const id = path.split('/').pop();
    const art = articles.value.find(a => a.id === id) || null;
    if (art) {
      selectArticle(art);
    } else {
      window.history.replaceState({}, '', '/');
      updateMeta();
    }
    return;
  }
  
  const pageMap = {
    '/blog': 'blog',
    '/gioi-thieu': 'gioi-thieu',
    '/legal/terms': 'legal-terms',
    '/legal/privacy': 'legal-privacy',
    '/legal/dmca': 'legal-dmca',
    '/admin': 'admin'
  };
  if (pageMap[path]) {
    openPage(pageMap[path]);
    return;
  }
  
  updateMeta();
});

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const handleToggleTheme = (isDark) => {
  if (isDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const openModal = () => {
  isModalOpen.value = true;
  isSubmitted.value = false;
  name.value = '';
  phone.value = '';
  link.value = '';

  formServiceIndex.value = '';

  errors.value = {};
};

const closeModal = () => {
  isModalOpen.value = false;
};

const openModalWithPackage = (pkgKey) => {
  openModal();
  formServiceIndex.value = pkgKey;
};

// Form state
const name = ref('');
const phone = ref('');
const link = ref('');
const formServiceIndex = ref("");
const isSubmitted = ref(false);

const selectedPriceDisplay = computed(() => {
  if (formServiceIndex.value && typeof formServiceIndex.value === 'string' && formServiceIndex.value.startsWith('package_')) {
    const pkg = pricingCards.find(card => card.packageKey === formServiceIndex.value);
    if (pkg) {
      return billingCycle.value === 'monthly' ? `${pkg.priceMonthly} ${pkg.unitMonthly}` : `${pkg.priceYearly} ${pkg.unitYearly}`;
    }
  }
  const svc = services[formServiceIndex.value] || services[currentServiceIndex.value];
  return svc ? svc.price : '1.5tr';
});

// Biến quản lý trạng thái Loading hiển thị ở nút bấm
const isLoading = ref(false);

// Contact section inline form state
const contactName = ref('');
const contactPhone = ref('');
const contactNote = ref('');
const contactRobot = ref(false);
const contactSubmitted = ref(false);
const contactLoading = ref(false);

// Hàm lọc ký tự không phải số và giới hạn tối đa 11 chữ số cho số điện thoại (thao tác DOM trực tiếp để triệt tiêu bộ gõ tiếng Việt Telex/IME)
const onPhoneInput = (event) => {
  const filtered = event.target.value.replace(/\D/g, '').slice(0, 11);
  phone.value = filtered;
  event.target.value = filtered;
};

const onContactPhoneInput = (event) => {
  const filtered = event.target.value.replace(/\D/g, '').slice(0, 11);
  contactPhone.value = filtered;
  event.target.value = filtered;
};

const submitToFormspree = async (payload) => {
  try {
    const formspreeId = import.meta.env.VITE_FORMSPREE_ID || 'xkgwboqe';
    await fetch(`https://formspree.io/f/${formspreeId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        name: payload.name,
        phone: payload.phone,
        message: `Link: ${payload.link || ''} | Service: ${payload.service || ''}`
      })
    });
  } catch (err) {
    console.error('Formspree fallback submission failed:', err);
  }
};

const triggerImmediateSocialProof = (payload) => {
  const nameParts = payload.name.trim().split(' ');
  const lastName = nameParts.pop() || '';
  const anonLastName = lastName.substring(0, 1) + '***';
  const anonName = nameParts.length > 0 
    ? nameParts.join(' ') + ' ' + anonLastName 
    : anonLastName;

  socialProofData.value = {
    name: anonName,
    location: 'Việt Nam',
    service: payload.service || 'Tư vấn tích xanh',
    time: 'Vừa xong'
  };
  
  setTimeout(() => {
    showSocialProof.value = true;
    setTimeout(() => {
      showSocialProof.value = false;
    }, 6000);
  }, 1500);

  fetchPublicSocialProofs();
};

const handleContactSubmit = async () => {
  if (!contactName.value.trim() || !contactPhone.value.trim()) return;
  contactLoading.value = true;
  const payload = {
    name: contactName.value,
    phone: contactPhone.value,
    link: contactNote.value.trim() || 'N/A', // link là required nên cần giá trị mặc định
    service: 'Tư vấn miễn phí'
  };

  try {
    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await response.json();
    if (response.ok && result.success) {
      contactSubmitted.value = true;
      triggerImmediateSocialProof(payload);
    } else {
      console.warn('Backend contact submission failed, falling back to Formspree:', result.message);
      await submitToFormspree(payload);
      contactSubmitted.value = true;
      triggerImmediateSocialProof(payload);
    }
  } catch (error) {
    console.error('Lỗi kết nối API, falling back to Formspree:', error);
    await submitToFormspree(payload);
    contactSubmitted.value = true;
    triggerImmediateSocialProof(payload);
  } finally {
    contactLoading.value = false;
  }
};

// Xử lý gửi Form (Gửi yêu cầu thực tế lên Backend API Laravel)
const handleSubmit = async () => {
  isLoading.value = true;
  let serviceName = 'Tư vấn tích xanh';
  if (formServiceIndex.value !== '') {
    if (typeof formServiceIndex.value === 'string' && formServiceIndex.value.startsWith('package_')) {
      const packageMap = {
        package_standard: 'Business Standard',
        package_plus: 'Business Plus',
        package_premium: 'Business Premium',
        package_max: 'Business Max (Gói Cao Cấp)'
      };
      serviceName = packageMap[formServiceIndex.value] || 'Tư vấn tích xanh';
    } else {
      const selectedService = services[formServiceIndex.value];
      if (selectedService) {
        serviceName = selectedService.name;
      }
    }
  } else {
    const selectedService = services[currentServiceIndex.value];
    if (selectedService) {
      serviceName = selectedService.name;
    }
  }

  const payload = {
    name: name.value,
    phone: phone.value,
    link: link.value,
    service: serviceName
  };

  try {
    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (response.ok && result.success) {
      isSubmitted.value = true;
      triggerImmediateSocialProof(payload);
    } else {
      console.warn('Backend contact submission failed, falling back to Formspree:', result.message);
      await submitToFormspree(payload);
      isSubmitted.value = true;
      triggerImmediateSocialProof(payload);
    }
  } catch (error) {
    console.error('Lỗi kết nối API, falling back to Formspree:', error);
    await submitToFormspree(payload);
    isSubmitted.value = true;
    triggerImmediateSocialProof(payload);
  } finally {
    isLoading.value = false;
  }
};

const validateForm = () => {
  const formErrors = {};
  if (!name.value.trim()) {
    formErrors.name = 'Vui lòng nhập họ và tên';
  }
  if (!phone.value.trim()) {
    formErrors.phone = 'Vui lòng nhập số điện thoại';
  } else if (!/^\d{9,11}$/.test(phone.value.replace(/\s+/g, ''))) {
    formErrors.phone = 'Số điện thoại không hợp lệ (9 - 11 chữ số)';
  }
  if (!link.value.trim()) {
    formErrors.link = 'Vui lòng nhập link tài khoản / Fanpage';
  }
  
  // THÊM ĐOẠN NÀY: Kiểm tra nếu ô dịch vụ bị bỏ trống
  if (formServiceIndex.value === "") {
    formErrors.service = 'Vui lòng chọn dịch vụ bạn quan tâm';
  }

  errors.value = formErrors;
  return Object.keys(formErrors).length === 0;
};



// Pricing cards data (matching target design)
const pricingCards = [
  {
    title: 'Business Standard',
    subtitle: 'Tích xanh cơ bản cho doanh nghiệp',
    priceMonthly: '450.000',
    priceYearly: '4.300.000',
    unitMonthly: 'VND/tháng²',
    unitYearly: 'VND/năm²',
    features: [
      'Huy hiệu đã xác minh (Tích xanh)',
      'Bảo vệ chống mạo danh tài khoản',
      'Dịch vụ hỗ trợ trực tuyến từ Meta (Chat hoặc gửi email)',
      'Áp dụng tối ưu hóa tìm kiếm trên Facebook & Instagram'
    ],
    metaFee: 'Thanh toán trực tiếp cho Meta Verified',
    btnText: 'Đăng ký Standard',
    isPopular: false,
    packageKey: 'package_standard'
  },
  {
    title: 'Business Plus',
    subtitle: 'Tính năng mở rộng & ưu tiên',
    priceMonthly: '1.250.000',
    priceYearly: '13.500.000',
    unitMonthly: 'VND/tháng²',
    unitYearly: 'VND/năm²',
    features: [
      'Bao gồm lợi ích Standard và:',
      'Được ưu tiên hỗ trợ giải quyết vấn đề nhanh hơn',
      'Mở rộng tính năng liên kết trang cá nhân (Chia sẻ đến 3 địa chỉ doanh nghiệp)',
      'Hỗ trợ liên kết nổi bật trong Thước phim (Reels)'
    ],
    metaFee: 'Thanh toán trực tiếp cho Meta Verified',
    btnText: 'Đăng ký Plus',
    isPopular: false,
    packageKey: 'package_plus'
  },
  {
    title: 'Business Premium',
    subtitle: 'Hỗ trợ trực tiếp từ tổng đài viên',
    priceMonthly: '3.300.000',
    priceYearly: '39.000.000',
    unitMonthly: 'VND/tháng²',
    unitYearly: 'VND/năm²',
    features: [
      'Bao gồm lợi ích Plus và:',
      'Dịch vụ hỗ trợ cao cấp: Yêu cầu tổng đài viên Meta gọi điện trực tiếp',
      'Mở rộng tính năng liên kết trang cá nhân (Chia sẻ đến 5 địa chỉ doanh nghiệp)',
      'Tăng số lượng liên kết trong Thước phim (4 liên kết mỗi tháng)'
    ],
    metaFee: 'Thanh toán trực tiếp cho Meta Verified',
    btnText: 'Đăng ký Premium',
    isPopular: true,
    packageKey: 'package_premium'
  },
  {
    title: 'Business Max',
    subtitle: 'Gói chuyên nghiệp cho DN lớn',
    priceMonthly: '9.800.000',
    priceYearly: '118.000.000',
    unitMonthly: 'VND/tháng²',
    unitYearly: 'VND/năm²',
    features: [
      'Bao gồm lợi ích Premium và:',
      'Gói chuyên nghiệp dành cho hệ thống doanh nghiệp lớn',
      'Mở rộng tối đa tính năng chia sẻ liên kết và vị trí doanh nghiệp'
    ],
    metaFee: 'Thanh toán trực tiếp cho Meta Verified',
    btnText: 'Đăng ký Max',
    isPopular: false,
    packageKey: 'package_max'
  }
];

// FAQ Accordion State and Data
const activeFaqIndexes = ref([0, 1, 2, 3, 4]); // First 5 open by default matching screenshot
const toggleFaq = (index) => {
  const position = activeFaqIndexes.value.indexOf(index);
  if (position > -1) {
    activeFaqIndexes.value.splice(position, 1);
  } else {
    activeFaqIndexes.value.push(index);
  }
};
const isFaqOpen = (index) => activeFaqIndexes.value.includes(index);

const faqs = [
  {
    question: "Đăng ký tích xanh mất bao lâu?",
    answer: "Chỉ 15-60 phút sau khi nhận đủ thông tin. Trường hợp đặc biệt có thể lên tới 24h, chúng tôi sẽ báo trước."
  },
  {
    question: "Fanpage mới tạo có đăng ký được không?",
    answer: "Có. Không giới hạn like/follower, không cần báo chí. Chỉ cần trang sạch, không vi phạm tiêu chuẩn cộng đồng."
  },
  {
    question: "Chi phí đăng ký cụ thể là bao nhiêu?",
    answer: "Chi phí đăng ký tùy thuộc vào gói dịch vụ Meta Verified doanh nghiệp bạn lựa chọn: gói Business Standard (450.000đ/tháng), Business Plus (1.250.000đ/tháng), Business Premium (3.300.000đ/tháng), hoặc Business Max (9.800.000đ/tháng). Có tùy chọn thanh toán hàng năm để tiết kiệm thêm."
  },
  {
    question: "Phí đăng ký là trọn gói hay còn gì thêm?",
    answer: "Mức giá trên là trọn gói phí dịch vụ Meta Verified được thanh toán trực tiếp qua cổng thanh toán của Meta. Chúng tôi cam kết hỗ trợ thiết lập, tối ưu hồ sơ doanh nghiệp và đồng hành trọn đời mà không phát sinh thêm chi phí ẩn."
  },
  {
    question: "Tích xanh có bị mất không?",
    answer: "Tích xanh được duy trì liên tục nếu bạn thanh toán phí hàng tháng cho Meta. Nếu có sự cố từ phía Meta, chúng tôi bảo hành miễn phí trọn đời."
  },
  {
    question: "Có an toàn không? Tài khoản có bị khóa không?",
    answer: "100% an toàn. Chúng tôi chỉ dùng phương pháp chính thức qua Meta Verified – không hack, không dùng công cụ bên thứ 3, không vi phạm chính sách."
  },
  {
    question: "Tích xanh khác tích xám như thế nào?",
    answer: "Tích xanh (Blue) dành cho cá nhân nổi tiếng, thương hiệu – mức độ uy tín vượt trội. Tích xám (Gray) chỉ xác minh doanh nghiệp đơn thuần. Dịch vụ của chúng tôi là tích xanh."
  },
  {
    question: "Nếu không thành công thì sao?",
    answer: "Hoàn tiền 100%. Cam kết 'làm xong mới thanh toán' nên bạn không mất gì ngay từ đầu."
  }
];
</script>

<template>
  <div id="top" class="min-h-screen bg-slate-50 dark:bg-[#060b13] text-slate-900 dark:text-slate-100 flex flex-col selection:bg-orange-500/30 selection:text-orange-900 dark:selection:bg-blue-600/30 dark:selection:text-blue-200 transition-colors duration-300">
    <!-- Header component -->
    <Header 
      :currentServiceIndex="currentServiceIndex" 
      @select-service="selectService" 
      @toggle-theme="handleToggleTheme" 
      @open-register="openModal"
      @navigate-section="handleNavigateSection"
    />

    <template v-if="!selectedArticle">
      <!-- ADMIN DASHBOARD PAGE -->
      <template v-if="selectedPage === 'admin'">
        <main class="min-h-screen bg-slate-50 dark:bg-[#060b13] text-slate-900 dark:text-slate-100 py-12 transition-colors duration-300">
          <!-- VERIFY PASSCODE STATE -->
          <div v-if="!isAdminAuthenticated" class="mx-auto max-w-md px-5 mt-12">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524]/60 backdrop-blur-xl p-8 shadow-2xl text-center transition-colors">
              <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-500 dark:text-blue-400 mb-6">
                <!-- Shield Lock Icon -->
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h1 class="text-2xl font-black text-slate-900 dark:text-white">Hệ Thống Quản Trị</h1>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Vui lòng nhập mật mã quản trị viên để tiếp tục truy cập danh sách đăng ký tư vấn.</p>
              
              <form @submit.prevent="verifyPasscode" class="mt-6 space-y-4">
                <div class="space-y-3">
                  <input 
                    type="text" 
                    v-model="loginUsername" 
                    placeholder="Tên đăng nhập" 
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                  />
                  <input 
                    type="password" 
                    v-model="loginPassword" 
                    placeholder="Mật khẩu" 
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                  />
                  <p v-if="adminError" class="mt-2 text-xs font-semibold text-rose-500 text-left flex items-center gap-1">
                    <span class="text-rose-500">⚠</span> {{ adminError }}
                  </p>
                </div>
                
                <button 
                  type="submit" 
                  class="w-full rounded-xl bg-blue-600 hover:bg-blue-500 px-4 py-3 text-sm font-extrabold text-white transition-all shadow-lg shadow-blue-600/20 active:scale-[0.98]"
                >
                  Xác Minh Quyền Truy Cập
                </button>
              </form>
            </div>
          </div>

          <!-- ADMIN DASHBOARD PANEL -->
          <div v-else-if="isAdminAuthenticated" class="mx-auto max-w-[1140px] px-5">
            <!-- Header section -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-slate-800 pb-6 mb-6">
              <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                  <span>Dashboard Quản Trị</span>
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-500 dark:text-blue-400">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Realtime {{ realtimeEnabled ? 'Đang bật' : 'Tắt' }}
                  </span>
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                  Đang đăng nhập với vai trò: 
                  <span class="font-extrabold text-blue-500 dark:text-blue-400">{{ adminRole === 'admin' ? 'Quản trị viên (Admin)' : 'Nhân viên (Employee)' }}</span> 
                  - {{ adminName }}
                </p>
              </div>
              
              <div class="flex items-center gap-3">
                <!-- Toggle Realtime -->
                <label v-if="activeAdminTab === 'leads'" class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" v-model="realtimeEnabled" class="sr-only peer">
                  <div class="w-9 h-5 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 peer-checked:after:bg-white"></div>
                  <span class="ml-2 text-xs font-semibold text-slate-500 dark:text-slate-400 select-none">Tự cập nhật</span>
                </label>

                <!-- Export CSV/Excel Button -->
                <button 
                  v-if="activeAdminTab === 'leads'"
                  @click="exportLeadsToExcel" 
                  class="flex h-9 items-center justify-center rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#0c1524] px-3 text-xs font-semibold text-slate-750 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors gap-1.5"
                  title="Xuất danh sách ra tệp Excel CSV"
                >
                  <Download class="h-3.5 w-3.5 text-emerald-500 dark:text-emerald-400" />
                  Xuất Excel
                </button>

                <!-- Refresh Button -->
                <button 
                  @click="activeAdminTab === 'leads' ? fetchLeads() : (activeAdminTab === 'chat' ? fetchChatSessions() : (activeAdminTab === 'blog' ? fetchRawBlogPosts() : fetchEmployees()))" 
                  class="flex h-9 items-center justify-center rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#0c1524] px-3 text-xs font-semibold text-slate-750 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors gap-1.5"
                >
                  <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3m0 0l3 3m-3-3v8" />
                  </svg>
                  Làm mới
                </button>

                <!-- Logout Button -->
                <button 
                  @click="logoutAdmin" 
                  class="flex h-9 items-center justify-center rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#0c1524] px-3 text-xs font-semibold text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 hover:border-rose-300 dark:hover:border-rose-900 transition-colors gap-1.5"
                >
                  <LogOut class="h-3.5 w-3.5" />
                  Đăng xuất
                </button>
              </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 mb-6 gap-2">
              <button 
                @click="activeAdminTab = 'leads'"
                class="px-4 py-2 text-xs font-bold transition-all border-b-2"
                :class="activeAdminTab === 'leads' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-850 dark:hover:text-slate-200'"
              >
                Đăng ký tư vấn
              </button>
              <button 
                v-if="adminRole === 'admin'"
                @click="activeAdminTab = 'employees'"
                class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-1.5"
                :class="activeAdminTab === 'employees' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-850 dark:hover:text-slate-200'"
              >
                <Users class="h-3.5 w-3.5" />
                Quản lý nhân viên
              </button>
              <button 
                v-if="adminRole === 'admin'"
                @click="activeAdminTab = 'blog'; fetchRawBlogPosts();"
                class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-1.5"
                :class="activeAdminTab === 'blog' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-850 dark:hover:text-slate-200'"
              >
                <BookOpen class="h-3.5 w-3.5" />
                Quản lý bài viết & SEO
              </button>
              <button 
                @click="activeAdminTab = 'chat'"
                class="px-4 py-2 text-xs font-bold transition-all border-b-2 flex items-center gap-1.5"
                :class="activeAdminTab === 'chat' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-850 dark:hover:text-slate-200'"
              >
                <MessageSquare class="h-3.5 w-3.5" />
                Hỗ trợ khách hàng
              </button>
            </div>

            <!-- TAB 1: LEADS (ĐĂNG KÝ) -->
            <div v-if="activeAdminTab === 'leads'" class="space-y-6">
              <!-- Stats Row -->
              <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 shadow-sm transition-colors">
                  <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tổng Đăng Ký</p>
                  <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ leads.length }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 shadow-sm transition-colors">
                  <p class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">Chờ Xử Lý</p>
                  <p class="mt-2 text-2xl font-black text-amber-500 dark:text-amber-400">{{ leads.filter(l => l.status === 'pending').length }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 shadow-sm transition-colors">
                  <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider">Đang Liên Hệ</p>
                  <p class="mt-2 text-2xl font-black text-blue-600 dark:text-blue-400">{{ leads.filter(l => l.status === 'contacting' || l.status === 'contacted').length }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 shadow-sm transition-colors">
                  <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Đã Hoàn Thành</p>
                  <p class="mt-2 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ leads.filter(l => l.status === 'completed').length }}</p>
                </div>
              </div>

              <!-- Leads Table / Mobile Grid -->
              <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524]/40 overflow-hidden transition-colors">
                <!-- Desktop Table view -->
                <div class="hidden md:block overflow-x-auto">
                   <table class="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">
                        <th class="p-4">Thời gian</th>
                        <th class="p-4">Họ và tên</th>
                        <th class="p-4">Số điện thoại</th>
                        <th class="p-4">Tài khoản / Link</th>
                        <th class="p-4">Dịch vụ</th>
                        <th class="p-4">Lời nhắn</th>
                        <th class="p-4">Trạng thái</th>
                        <th v-if="adminRole === 'admin'" class="p-4 text-right">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="lead in leads" :key="lead.id" class="border-b border-slate-100 dark:border-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium whitespace-nowrap">
                          {{ new Date(lead.created_at).toLocaleString('vi-VN', {hour: '2-digit', minute:'2-digit', day:'2-digit', month:'2-digit'}) }}
                        </td>
                        <td class="p-4">
                          <button 
                            @click="openLeadDetail(lead)" 
                            class="font-black text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 hover:underline text-[13px] text-left focus:outline-none transition-colors"
                            title="Xem thông tin chi tiết khách hàng"
                          >
                            {{ lead.name }}
                          </button>
                        </td>
                        <td class="p-4 whitespace-nowrap">
                          <div class="flex flex-col gap-1">
                            <a 
                              :href="'tel:' + lead.phone" 
                              class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all"
                              title="Click để gọi điện thoại trên di động"
                            >
                              <Phone class="h-3.5 w-3.5" />
                              <span class="tracking-wider">{{ lead.phone }}</span>
                            </a>
                            <a 
                              :href="'https://zalo.me/' + lead.phone" 
                              target="_blank" 
                              class="inline-flex items-center justify-center gap-1 rounded bg-blue-600/10 hover:bg-blue-600 px-2 py-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-white transition-all"
                              title="Mở Zalo chat"
                            >
                              <MessageSquare class="h-3 w-3" />
                              Zalo chat
                            </a>
                          </div>
                        </td>
                        <td class="p-4">
                          <div class="flex flex-col gap-1">
                            <a 
                              :href="lead.link" 
                              target="_blank" 
                              class="inline-flex items-center gap-1 max-w-[200px] truncate text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                              :title="lead.link"
                            >
                              <span class="truncate font-semibold">{{ lead.link }}</span>
                              <ExternalLink class="h-3.5 w-3.5 flex-shrink-0" />
                            </a>
                            <div class="flex gap-1">
                              <span class="rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider scale-90 origin-left">
                                {{ lead.link.includes('facebook.com') || lead.link.includes('fb.com') ? 'Facebook' : 'Link/Website' }}
                              </span>
                            </div>
                          </div>
                        </td>
                        <td class="p-4 font-semibold text-slate-700 dark:text-slate-300">{{ lead.service }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400 max-w-[200px] truncate" :title="lead.message">{{ lead.message || '-' }}</td>
                        <td class="p-4 whitespace-nowrap">
                          <select 
                            :value="lead.status" 
                            @change="updateLeadStatus(lead.id, $event.target.value)"
                            class="rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-2 py-1 text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none transition-colors"
                            :class="{
                              'text-amber-500 border-amber-500/30 bg-amber-500/5': lead.status === 'pending',
                              'text-blue-500 dark:text-blue-400 border-blue-555/30 bg-blue-500/5': lead.status === 'contacting' || lead.status === 'contacted',
                              'text-emerald-500 dark:text-emerald-400 border-emerald-500/30 bg-emerald-500/5': lead.status === 'completed'
                            }"
                          >
                            <option value="pending">Chờ xử lý</option>
                            <option value="contacting">Đang liên hệ</option>
                            <option value="completed">Đã hoàn thành</option>
                          </select>
                        </td>
                        <td v-if="adminRole === 'admin'" class="p-4 text-right">
                          <button 
                            @click="deleteLead(lead.id)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-all active:scale-95"
                            title="Xóa lượt đăng ký"
                          >
                            <Trash2 class="h-3.5 w-3.5" />
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Mobile Grid Card view -->
                <div class="block md:hidden divide-y divide-slate-200 dark:divide-slate-800">
                  <div v-for="lead in leads" :key="lead.id" class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                      <span class="text-[10px] font-bold text-slate-500">
                        {{ new Date(lead.created_at).toLocaleString('vi-VN', {hour: '2-digit', minute:'2-digit', day:'2-digit', month:'2-digit'}) }}
                      </span>
                      <button 
                        v-if="adminRole === 'admin'"
                        @click="deleteLead(lead.id)"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-all"
                      >
                        <Trash2 class="h-3.5 w-3.5" />
                      </button>
                    </div>
                    
                    <div>
                      <button 
                        @click="openLeadDetail(lead)"
                        class="font-black text-slate-900 dark:text-white text-[15px] text-left hover:text-blue-600 dark:hover:text-blue-400 hover:underline focus:outline-none"
                      >
                        {{ lead.name }}
                      </button>
                      <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ lead.service }}</p>
                      <p v-if="lead.message" class="text-xs text-slate-550 dark:text-slate-400 mt-1 italic">"{{ lead.message }}"</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 pt-1">
                      <div class="grid grid-cols-2 gap-2">
                        <a 
                          :href="'tel:' + lead.phone" 
                          class="flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 py-2.5 text-xs font-bold text-white transition-all shadow-lg active:scale-95"
                        >
                          <Phone class="h-3.5 w-3.5" />
                          Gọi điện
                        </a>
                        <a 
                          :href="'https://zalo.me/' + lead.phone" 
                          target="_blank"
                          class="flex items-center justify-center gap-1.5 rounded-xl bg-blue-600/10 border border-blue-500/25 py-2.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all"
                        >
                          <MessageSquare class="h-3.5 w-3.5" />
                          Zalo
                        </a>
                      </div>

                      <a :href="lead.link" target="_blank" class="flex items-center justify-center gap-2 rounded-xl border border-slate-350 dark:border-slate-800 bg-slate-50 dark:bg-[#060b13] py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <span>Facebook / Link đăng ký</span>
                        <ExternalLink class="h-3.5 w-3.5" />
                      </a>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-200 dark:border-slate-900/60 pt-3">
                      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Trạng thái:</span>
                      <select 
                        :value="lead.status" 
                        @change="updateLeadStatus(lead.id, $event.target.value)"
                        class="rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-2.5 py-1 text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none transition-colors"
                        :class="{
                          'text-amber-500 dark:text-amber-400 border-amber-500/30 bg-amber-500/5': lead.status === 'pending',
                          'text-blue-500 dark:text-blue-400 border-blue-500/30 bg-blue-500/5': lead.status === 'contacting' || lead.status === 'contacted',
                          'text-emerald-500 dark:text-emerald-400 border-emerald-500/30 bg-emerald-500/5': lead.status === 'completed'
                        }"
                      >
                        <option value="pending">Chờ xử lý</option>
                        <option value="contacting">Đang liên hệ</option>
                        <option value="completed">Đã hoàn thành</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <!-- Empty State -->
                <div v-if="leads.length === 0" class="text-center py-12 px-5 transition-colors">
                  <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4a2 2 0 00-2 2v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1a2 2 0 00-2-2H2" />
                    </svg>
                  </div>
                  <h3 class="text-sm font-bold text-slate-900 dark:text-white">Chưa có lượt đăng ký nào</h3>
                  <p class="text-xs text-slate-550 dark:text-slate-500 mt-1">Thông tin khách hàng đăng ký tư vấn sẽ xuất hiện tại đây.</p>
                </div>
              </div>
            </div>

            <!-- TAB 2: EMPLOYEES (NHÂN VIÊN) -->
            <div v-else-if="activeAdminTab === 'employees' && adminRole === 'admin'" class="space-y-6">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Danh Sách Nhân Viên</h2>
                <button 
                  @click="openAddEmployeeModal"
                  class="flex h-9 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-bold text-white hover:bg-blue-500 transition-colors gap-1.5"
                >
                  <Plus class="h-3.5 w-3.5" />
                  Thêm nhân viên
                </button>
              </div>

              <!-- Employees Table -->
              <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524]/40 overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">
                        <th class="p-4">Họ và tên</th>
                        <th class="p-4">Mật mã đăng nhập</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Ngày tạo</th>
                        <th class="p-4 text-right">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="emp in employeesList" :key="emp.id" class="border-b border-slate-100 dark:border-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="p-4 font-black text-slate-900 dark:text-white text-[13px]">{{ emp.name }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400 font-mono select-all">{{ emp.passcode }}</td>
                        <td class="p-4">
                          <span 
                            class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold"
                            :class="emp.status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'"
                          >
                            {{ emp.status === 'active' ? 'Hoạt động' : 'Tạm khóa' }}
                          </span>
                        </td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">
                          {{ new Date(emp.created_at).toLocaleDateString('vi-VN') }}
                        </td>
                        <td class="p-4 text-right space-x-2">
                          <button 
                            @click="openEditEmployeeModal(emp)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all"
                            title="Sửa thông tin"
                          >
                            <Edit class="h-3.5 w-3.5" />
                          </button>
                          <button 
                            @click="deleteEmployee(emp.id)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-all"
                            title="Xóa nhân viên"
                          >
                            <Trash2 class="h-3.5 w-3.5" />
                          </button>
                        </td>
                      </tr>
                      <tr v-if="employeesList.length === 0">
                        <td colspan="5" class="p-8 text-center text-slate-500">
                          Chưa có tài khoản nhân viên nào. Hãy thêm mới để nhân viên có thể đăng nhập.
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- TAB: BLOG & SEO MANAGEMENT -->
            <div v-else-if="activeAdminTab === 'blog' && adminRole === 'admin'" class="space-y-6">
              <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Quản Lý Bài Viết & SEO</h2>
                <button 
                  @click="openAddBlogModal"
                  class="flex h-9 items-center justify-center rounded-lg bg-blue-600 px-4 text-xs font-bold text-white hover:bg-blue-500 transition-colors gap-1.5"
                >
                  <Plus class="h-3.5 w-3.5" />
                  Viết bài mới
                </button>
              </div>

              <!-- Blog Posts Table -->
              <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524]/40 overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">
                        <th class="p-4">Bài viết</th>
                        <th class="p-4">Tóm tắt / SEO Keywords</th>
                        <th class="p-4">SEO Meta Title & Desc</th>
                        <th class="p-4">Ngày tạo</th>
                        <th class="p-4 text-right">Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="post in rawBlogPosts" :key="post.id" class="border-b border-slate-100 dark:border-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="p-4 flex items-start gap-3">
                          <img 
                            :src="post.image_url || 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=800&q=80'" 
                            class="w-12 h-12 object-cover rounded-lg border border-slate-200 dark:border-slate-800"
                            alt="Banner" 
                          />
                          <div class="flex flex-col">
                            <span class="font-black text-slate-900 dark:text-white text-[13px] line-clamp-1">{{ post.title }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">slug: {{ post.slug }}</span>
                          </div>
                        </td>
                        <td class="p-4 max-w-[200px]">
                          <p class="text-slate-600 dark:text-slate-350 line-clamp-2 leading-relaxed">{{ post.excerpt || 'Không có tóm tắt.' }}</p>
                          <div class="mt-1 flex items-center gap-1">
                            <span class="text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-1.5 py-0.5 rounded">
                              {{ post.meta_keywords || 'Chưa có từ khóa SEO' }}
                            </span>
                          </div>
                        </td>
                        <td class="p-4 max-w-[250px]">
                          <div class="flex flex-col gap-0.5">
                            <span class="font-bold text-slate-850 dark:text-slate-300 text-[11px] line-clamp-1" :class="post.meta_title ? '' : 'text-amber-500 dark:text-amber-450 italic'">
                              {{ post.meta_title || 'Thiếu SEO Title' }}
                            </span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-tight">
                              {{ post.meta_description || 'Chưa thiết lập mô tả SEO' }}
                            </span>
                          </div>
                        </td>
                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium">
                          {{ new Date(post.created_at).toLocaleDateString('vi-VN') }}
                        </td>
                        <td class="p-4 text-right space-x-2 whitespace-nowrap">
                          <button 
                            @click="openEditBlogModal(post)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all"
                            title="Chỉnh sửa bài viết & SEO"
                          >
                            <Edit class="h-3.5 w-3.5" />
                          </button>
                          <button 
                            @click="deleteBlogPost(post.id)"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-rose-500/10 text-rose-500 dark:text-rose-400 hover:bg-rose-500 hover:text-white transition-all"
                            title="Xóa bài viết"
                          >
                            <Trash2 class="h-3.5 w-3.5" />
                          </button>
                        </td>
                      </tr>
                      <tr v-if="rawBlogPosts.length === 0">
                        <td colspan="5" class="p-8 text-center text-slate-500">
                          Chưa có bài viết nào trên hệ thống. Hãy tạo bài viết đầu tiên của bạn!
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- TAB 3: CUSTOMER CHAT (TIN NHẮN KHÁCH HÀNG) -->
            <div v-else-if="activeAdminTab === 'chat'" class="grid grid-cols-1 md:grid-cols-12 gap-6 h-[600px] border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden bg-white dark:bg-[#0c1524]/20 backdrop-blur-xl transition-colors">
              <!-- Left Panel: Session list -->
              <div class="md:col-span-4 border-r border-slate-200 dark:border-slate-800 flex flex-col h-full bg-slate-50/50 dark:bg-[#0c1524]/40">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 font-black text-slate-900 dark:text-white text-sm flex items-center justify-between">
                  <span>Phiên Hội Thoại</span>
                  <span class="text-[10px] text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full font-bold">
                    {{ chatSessions.length }}
                  </span>
                </div>
                
                <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-900/60">
                  <div 
                    v-for="session in chatSessions" 
                    :key="session.session_id"
                    @click="selectChatSession(session.session_id)"
                    class="p-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800/30 transition-all flex flex-col gap-1"
                    :class="activeSessionId === session.session_id ? 'bg-blue-500/10 dark:bg-blue-500/10 border-l-4 border-blue-500' : ''"
                  >
                    <div class="flex items-center justify-between">
                      <span class="font-extrabold text-[13px]" :class="session.unread_count > 0 ? 'text-slate-900 dark:text-white font-black' : 'text-slate-700 dark:text-slate-300'">
                        {{ session.customer_name }}
                      </span>
                      <span class="text-[9px] text-slate-500 font-medium whitespace-nowrap">
                        {{ new Date(session.updated_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'}) }}
                      </span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                      <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate flex-1">
                        {{ session.last_message }}
                      </p>
                      <span 
                        v-if="session.unread_count > 0"
                        class="inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[8px] font-bold text-white"
                      >
                        {{ session.unread_count }}
                      </span>
                    </div>
                  </div>
                  <div v-if="chatSessions.length === 0" class="text-center p-8 text-slate-500 text-xs">
                    Chưa có cuộc trò chuyện nào từ khách hàng.
                  </div>
                </div>
              </div>

              <!-- Right Panel: Message Thread -->
              <div class="md:col-span-8 flex flex-col h-full bg-slate-100/30 dark:bg-slate-950/20">
                <!-- Thread Header -->
                <div v-if="activeSessionId" class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-[#0c1524]/20">
                  <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-[13px]">
                      {{ chatSessions.find(s => s.session_id === activeSessionId)?.customer_name || 'Đang hội thoại' }}
                    </h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">ID: {{ activeSessionId }}</p>
                  </div>
                </div>
                
                <!-- Messages Stream -->
                <div 
                  id="admin-chat-messages" 
                  class="flex-1 p-4 overflow-y-auto space-y-4"
                >
                  <div v-if="!activeSessionId" class="h-full flex flex-col items-center justify-center text-slate-500 text-center p-6">
                    <MessageSquare class="h-10 w-10 text-slate-600 mb-2" />
                    <p class="text-xs font-semibold">Vui lòng chọn một phiên hội thoại ở cột trái để bắt đầu chat với khách hàng.</p>
                  </div>
                  
                  <template v-else>
                    <div 
                      v-for="msg in activeSessionMessages" 
                      :key="msg.id"
                      class="flex flex-col max-w-[80%]"
                      :class="msg.sender_type === 'employee' || msg.sender_type === 'admin' ? 'ml-auto items-end' : 'items-start'"
                    >
                      <span class="text-[9px] text-slate-500 mb-0.5 px-1 font-semibold">
                        {{ msg.sender_type === 'customer' ? msg.customer_name : (msg.sender_name || 'Quản trị viên') }} • {{ new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'}) }}
                      </span>
                      <div 
                        class="rounded-2xl px-4 py-2.5 text-xs text-left"
                        :class="msg.sender_type === 'employee' || msg.sender_type === 'admin' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-transparent rounded-tl-none'"
                      >
                        {{ msg.message }}
                      </div>
                    </div>
                  </template>
                </div>

                <!-- Input area -->
                <div v-if="activeSessionId" class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-[#0c1524]/40">
                  <form @submit.prevent="sendAdminMessage" class="flex gap-2">
                    <input 
                      type="text" 
                      v-model="adminChatInput"
                      placeholder="Nhập nội dung tin nhắn gửi khách hàng..."
                      class="flex-1 rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                    />
                    <button 
                      type="submit"
                      class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition-all active:scale-95 flex-shrink-0"
                    >
                      <Send class="h-3.5 w-3.5" />
                    </button>
                  </form>
                </div>
              </div>
            </div>

            <!-- Employee Form Modal -->
            <div v-if="showEmployeeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
              <div class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-6 shadow-2xl transition-colors">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-4">
                  <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    {{ employeeForm.id ? 'Cập Nhật Nhân Viên' : 'Thêm Nhân Viên Mới' }}
                  </h3>
                  <button @click="showEmployeeModal = false" class="text-slate-400 hover:text-slate-650 dark:hover:text-white">
                    <X class="h-5 w-5" />
                  </button>
                </div>
                
                <form @submit.prevent="saveEmployee" class="space-y-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Tên nhân viên</label>
                    <input 
                      type="text" 
                      v-model="employeeForm.name" 
                      placeholder="Nhập tên đầy đủ"
                      class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                    />
                    <p v-if="employeeErrors.name" class="mt-1 text-[11px] text-rose-500 font-semibold">{{ employeeErrors.name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Mật mã đăng nhập</label>
                    <input 
                      type="text" 
                      v-model="employeeForm.passcode" 
                      placeholder="Mật mã đăng nhập duy nhất"
                      class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none font-mono transition-colors"
                    />
                    <p v-if="employeeErrors.passcode" class="mt-1 text-[11px] text-rose-500 font-semibold">{{ employeeErrors.passcode }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Trạng thái</label>
                    <select 
                      v-model="employeeForm.status"
                      class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none transition-colors"
                    >
                      <option value="active">Hoạt động (Active)</option>
                      <option value="inactive">Tạm khóa (Inactive)</option>
                    </select>
                  </div>
                  
                  <div class="flex justify-end gap-3 pt-2">
                    <button 
                      type="button" 
                      @click="showEmployeeModal = false"
                      class="rounded-xl border border-slate-300 dark:border-slate-800 px-4 py-2 text-xs font-bold text-slate-650 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    >
                      Hủy bỏ
                    </button>
                    <button 
                      type="submit" 
                      class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-500 transition-colors"
                    >
                      Lưu thông tin
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Blog Form Modal -->
            <div v-if="showBlogModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
              <div class="w-full max-w-4xl rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-6 shadow-2xl transition-colors my-8 flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-4 flex-shrink-0">
                  <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <BookOpen class="h-5 w-5 text-blue-500" />
                    {{ blogForm.id ? 'Cập Nhật Bài Viết & SEO' : 'Viết Bài Mới' }}
                  </h3>
                  <button @click="showBlogModal = false" class="text-slate-400 hover:text-slate-650 dark:hover:text-white">
                    <X class="h-5 w-5" />
                  </button>
                </div>
                
                <form @submit.prevent="saveBlogPost" class="space-y-6 flex-1 overflow-y-auto pr-1 pb-4">
                  <!-- Row 1: Title & Image URL -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Tiêu đề bài viết</label>
                      <input 
                        type="text" 
                        v-model="blogForm.title" 
                        placeholder="Nhập tiêu đề bài viết"
                        class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                      />
                      <p v-if="blogErrors.title" class="mt-1 text-[11px] text-rose-500 font-semibold">{{ blogErrors.title }}</p>
                    </div>
                    <div>
                      <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Đường dẫn ảnh đại diện (Banner Image URL)</label>
                      <input 
                        type="text" 
                        v-model="blogForm.image_url" 
                        placeholder="Nhập liên kết ảnh Unsplash hoặc ảnh tải lên"
                        class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                      />
                    </div>
                  </div>

                  <!-- Row 2: Excerpt -->
                  <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Tóm tắt ngắn (Excerpt)</label>
                    <textarea 
                      v-model="blogForm.excerpt" 
                      rows="2"
                      placeholder="Nhập tóm tắt ngắn hiển thị trên danh sách tin tức (dưới 500 ký tự)"
                      class="w-full rounded-xl border border-slate-355 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                    ></textarea>
                  </div>

                  <!-- Row 3: Content -->
                  <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Nội dung bài viết (Hỗ trợ HTML)</label>
                    <textarea 
                      v-model="blogForm.content" 
                      rows="8"
                      placeholder="Nhập nội dung bài viết chi tiết..."
                      class="w-full rounded-xl border border-slate-355 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none font-mono transition-colors"
                    ></textarea>
                    <p v-if="blogErrors.content" class="mt-1 text-[11px] text-rose-500 font-semibold">{{ blogErrors.content }}</p>
                  </div>

                  <!-- Row 4: SEO Metadata Section -->
                  <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-900/40 space-y-4">
                    <div class="flex items-center gap-1.5 border-b border-slate-200 dark:border-slate-800 pb-2 mb-2">
                      <Globe class="h-4 w-4 text-emerald-500" />
                      <span class="text-xs font-black text-slate-850 dark:text-slate-200 uppercase tracking-wider">Cấu hình SEO Meta Tags</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Meta Title (Tiêu đề SEO)</label>
                        <input 
                          type="text" 
                          v-model="blogForm.meta_title" 
                          placeholder="Mặc định sử dụng tiêu đề bài viết"
                          class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                        />
                      </div>
                      <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Meta Keywords (SEO Từ khóa)</label>
                        <input 
                          type="text" 
                          v-model="blogForm.meta_keywords" 
                          placeholder="Ví dụ: facebook verification, tich xanh, az media"
                          class="w-full rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                        />
                      </div>
                    </div>

                    <div>
                      <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Meta Description (Mô tả SEO)</label>
                      <textarea 
                        v-model="blogForm.meta_description" 
                        rows="2"
                        placeholder="Nhập mô tả SEO phục vụ kết quả tìm kiếm Google (dưới 500 ký tự)"
                        class="w-full rounded-xl border border-slate-355 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
                      ></textarea>
                    </div>
                  </div>

                  <!-- Footer Buttons inside form -->
                  <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800 flex-shrink-0">
                    <button 
                      type="button" 
                      @click="showBlogModal = false"
                      class="rounded-xl border border-slate-300 dark:border-slate-800 px-4 py-2 text-xs font-bold text-slate-650 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    >
                      Hủy bỏ
                    </button>
                    <button 
                      type="submit" 
                      class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-500 transition-colors shadow-lg shadow-blue-500/15"
                    >
                      {{ blogForm.id ? 'Cập nhật bài viết' : 'Lưu & xuất bản' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </main>
      </template>

      <!-- BLOG PAGE -->
      <template v-else-if="selectedPage === 'blog'">
        <main class="min-h-screen bg-slate-50 dark:bg-[#060b13] text-slate-900 dark:text-slate-100 py-12 transition-colors duration-300">
          <div class="mx-auto max-w-[760px] px-5">
            <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500">
              <button @click="closePage" class="hover:text-blue-500">Trang chủ</button>
              <span>/</span>
              <span class="text-slate-700 dark:text-slate-300">Tin tức & Kiến thức</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Tin Tức & Kiến Thức</h1>
            <p class="mt-2 text-[13px] font-medium text-slate-600 dark:text-slate-400">Cập nhật mới nhất về đăng ký tích xanh Facebook, Instagram, TikTok & WhatsApp</p>
            
            <div class="mt-8 grid gap-6 sm:grid-cols-2">
              <article v-for="item in articles" :key="item.id"
                class="group cursor-pointer rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 shadow-sm transition-all duration-300 hover:border-blue-500/50 hover:shadow-lg hover:shadow-blue-500/5 hover:-translate-y-1"
                @click="selectArticle(item)">
                <img :src="item.banner" :alt="item.title" class="mb-4 h-40 w-full rounded-xl object-cover" loading="lazy" />
                <span class="inline-block rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600 dark:text-blue-400">{{ item.category }}</span>
                <h2 class="mt-2 text-[14px] font-black leading-snug text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ item.title }}</h2>
                <p class="mt-1.5 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400 line-clamp-2">{{ item.description }}</p>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-semibold text-slate-500">
                  <span>{{ item.author }}</span><span>·</span><span>{{ item.date }}</span><span>·</span><span>{{ item.readTime }}</span>
                </div>
              </article>
            </div>
            
            <div class="mt-12 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center text-white shadow-xl shadow-blue-500/10">
              <p class="text-lg font-black">Cần hỗ trợ đăng ký tích xanh?</p>
              <p class="text-sm text-blue-100 mt-1">Liên hệ ngay để được chuyên viên AZ Media kiểm tra hồ sơ miễn phí</p>
              <a href="tel:0968825068" class="mt-4 inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-extrabold text-blue-600 hover:bg-blue-50 transition-all duration-300 shadow-md">
                Gọi 0968.825.068
              </a>
            </div>
          </div>
        </main>
      </template>

      <!-- GIỚI THIỆU PAGE -->
      <template v-else-if="selectedPage === 'gioi-thieu'">
        <main class="min-h-screen bg-slate-50 dark:bg-[#060b13] text-slate-900 dark:text-slate-100 py-12 transition-colors duration-300">
          <div class="mx-auto max-w-[760px] px-5">
            <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500">
              <button @click="closePage" class="hover:text-blue-500">Trang chủ</button>
              <span>/</span>
              <span class="text-slate-700 dark:text-slate-300">Giới thiệu</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Về Chúng Tôi</h1>
            <p class="mt-4 text-[14px] font-medium leading-7 text-slate-700 dark:text-slate-300">
              <strong>AZ Media</strong> là đơn vị chuyên tư vấn và hỗ trợ đăng ký tích xanh (Meta Verified) cho Fanpage Facebook, Instagram, TikTok và WhatsApp tại Việt Nam. Với 5+ năm kinh nghiệm, chúng tôi đã hỗ trợ hơn <strong>2.368 khách hàng</strong> thành công.
            </p>
            
            <div class="mt-8 grid gap-4 grid-cols-3">
              <div v-for="[num, label] in [['2.368+', 'Khách hàng'], ['100%', 'Tỉ lệ thành công'], ['5+', 'Năm kinh nghiệm']]" :key="label"
                class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-5 text-center shadow-sm transition-colors">
                <p class="text-2xl font-black text-blue-650 dark:text-blue-400">{{ num }}</p>
                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ label }}</p>
              </div>
            </div>
            
            <div class="mt-8 space-y-6 text-[14px] font-medium leading-7 text-slate-700 dark:text-slate-300">
              <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Cam kết của chúng tôi</h2>
              <ul class="space-y-3.5">
                <li v-for="item in ['Làm xong tích xanh mới thu phí — không mất tiền oan', 'Không yêu cầu mật khẩu, đảm bảo an toàn tài khoản', 'Hỗ trợ 24/7, bảo hành trọn đời', 'Chỉ dùng phương pháp chính thức của Meta']" :key="item"
                  class="flex items-start gap-2.5">
                  <span class="mt-0.5 text-emerald-500 dark:text-emerald-400">✓</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
              
              <h2 class="mt-8 text-xl font-extrabold text-slate-900 dark:text-white">Thông tin công ty</h2>
              <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1524] p-6 space-y-3 text-[13px] transition-colors">
                <p><strong class="text-slate-900 dark:text-white">Mã số doanh nghiệp:</strong> 0314247738 — 002</p>
                <p><strong class="text-slate-900 dark:text-white">Địa chỉ ĐKKD:</strong> 127 Lê Trọng Tấn, Phường An Khê, Đà Nẵng</p>
                <p><strong class="text-slate-900 dark:text-white">Văn phòng giao dịch:</strong> 84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng</p>
                <p><strong class="text-slate-900 dark:text-white">Điện thoại:</strong> <a href="tel:0968825068" class="text-blue-650 dark:text-blue-400 hover:underline">0968.825.068</a> (Mr. Quang — CEO)</p>
                <p><strong class="text-slate-900 dark:text-white">Email:</strong> <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-650 dark:text-blue-400 hover:underline">azmedia.com.vn@gmail.com</a></p>
                <p><strong class="text-slate-900 dark:text-white">Facebook:</strong> <a href="https://fb.com/congtyazmedia" target="_blank" class="text-blue-650 dark:text-blue-400 hover:underline">fb.com/congtyazmedia</a></p>
              </div>
            </div>
          </div>
        </main>
      </template>

      <!-- LEGAL PAGES -->
      <template v-else-if="selectedPage && selectedPage.startsWith('legal-')">
        <main class="min-h-screen bg-slate-50 dark:bg-[#060b13] text-slate-900 dark:text-slate-100 py-12 transition-colors duration-300">
          <div class="mx-auto max-w-[760px] px-5">
            <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500">
              <button @click="closePage" class="hover:text-blue-500">Trang chủ</button>
              <span>/</span>
              <span class="text-slate-700 dark:text-slate-300">{{ selectedPage === 'legal-terms' ? 'Điều khoản dịch vụ' : selectedPage === 'legal-privacy' ? 'Chính sách bảo mật' : 'DMCA' }}</span>
            </nav>
            
            <!-- TERMS -->
            <template v-if="selectedPage === 'legal-terms'">
              <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Điều Khoản Dịch Vụ</h1>
              <div class="mt-6 space-y-5 text-[14px] leading-7 text-slate-700 dark:text-slate-300">
                <p>Bằng việc sử dụng dịch vụ của <strong>Đăng Ký Tích Xanh (AZ Media)</strong>, bạn đồng ý với các điều khoản sau:</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">1. Phạm vi dịch vụ</h2>
                <p>Chúng tôi cung cấp dịch vụ tư vấn và hỗ trợ đăng ký tích xanh (Meta Verified) cho Fanpage Facebook, Instagram, TikTok và WhatsApp Business thông qua các phương pháp chính thức của Meta.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">2. Chính sách thanh toán</h2>
                <p>Khách hàng <strong>chỉ thanh toán sau khi tích xanh đã hiển thị thành công</strong> trên tài khoản. Không thu bất kỳ khoản tiền nào trước khi hoàn thành.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">3. Bảo hành & hỗ trợ</h2>
                <p>Chúng tôi cam kết hỗ trợ trọn đời trong trường hợp tích xanh bị mất do lỗi kỹ thuật từ phía chúng tôi.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">4. Giới hạn trách nhiệm</h2>
                <p>Chúng tôi không chịu trách nhiệm cho các thay đổi chính sách đột ngột từ phía Meta/Facebook làm ảnh hưởng đến tích xanh sau khi đã hoàn thành dịch vụ.</p>
              </div>
            </template>
            
            <!-- PRIVACY -->
            <template v-else-if="selectedPage === 'legal-privacy'">
              <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Chính Sách Bảo Mật</h1>
              <div class="mt-6 space-y-5 text-[14px] leading-7 text-slate-700 dark:text-slate-300">
                <p>Chúng tôi cam kết bảo vệ thông tin cá nhân của khách hàng theo các nguyên tắc sau:</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">1. Thông tin thu thập</h2>
                <p>Chúng tôi chỉ thu thập: Họ tên, số điện thoại, link Fanpage/tài khoản cần đăng ký. <strong>Tuyệt đối không yêu cầu mật khẩu.</strong></p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">2. Mục đích sử dụng</h2>
                <p>Thông tin chỉ được dùng để liên hệ tư vấn và thực hiện dịch vụ. Không chia sẻ cho bên thứ ba.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">3. Bảo mật dữ liệu</h2>
                <p>Thông tin khách hàng được lưu trữ an toàn và xóa sau khi hoàn thành dịch vụ theo yêu cầu.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">4. Liên hệ</h2>
                <p>Mọi thắc mắc về bảo mật: <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-650 dark:text-blue-400 hover:underline">azmedia.com.vn@gmail.com</a></p>
              </div>
            </template>
            
            <!-- DMCA -->
            <template v-else>
              <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">DMCA</h1>
              <div class="mt-6 space-y-5 text-[14px] leading-7 text-slate-700 dark:text-slate-300">
                <p>Tất cả nội dung trên website <strong>dangkytichxanh.vn</strong> bao gồm văn bản, hình ảnh, logo và thiết kế đều thuộc quyền sở hữu của AZ Media.</p>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mt-6">Báo cáo vi phạm bản quyền</h2>
                <p>Nếu bạn phát hiện nội dung của chúng tôi bị sao chép trái phép, vui lòng liên hệ:</p>
                <ul class="space-y-1.5 pl-4 list-disc">
                  <li>Email: <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-650 dark:text-blue-400 hover:underline">azmedia.com.vn@gmail.com</a></li>
                  <li>Hotline: <a href="tel:0968825068" class="text-blue-650 dark:text-blue-400 hover:underline">0968.825.068</a></li>
                </ul>
                <p>Chúng tôi sẽ xử lý trong vòng 72 giờ làm việc.</p>
              </div>
            </template>
          </div>
        </main>
      </template>

      <!-- MAIN LANDING PAGE -->
      <template v-else>
        <!-- Hero component -->
        <HeroSection 
          :service="services[currentServiceIndex]" 
          @open-register="openModal" 
        />

    <!-- Stats Bar component -->
    <StatsBar />

    <!-- Why Us component -->
    <WhyUs />

    <main class="w-full">
      <!-- Bảng giá (Pricing) Section -->
      <section id="bang-gia" class="py-20 bg-slate-50 dark:bg-[#060b13] relative overflow-hidden transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
              Bảng giá dịch vụ Tích Xanh
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-medium">
              Làm xong mới thanh toán, cam kết 100% thành công
            </p>
          </div>

          <!-- Billing cycle toggle -->
          <div class="flex items-center justify-center gap-4 mb-12 select-none">
            <button 
              @click="billingCycle = 'monthly'"
              class="text-sm font-extrabold transition-colors duration-200"
              :class="billingCycle === 'monthly' ? 'text-orange-500' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
            >
              Hàng tháng
            </button>
            <div 
              @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'"
              class="w-14 h-7 rounded-full bg-slate-200 dark:bg-slate-800 p-1 cursor-pointer transition-colors duration-300 flex items-center relative"
            >
              <div 
                class="w-5 h-5 rounded-full bg-orange-500 transition-all duration-300 shadow-md"
                :style="{ transform: billingCycle === 'monthly' ? 'translateX(0)' : 'translateX(28px)' }"
              ></div>
            </div>
            <button 
              @click="billingCycle = 'yearly'"
              class="text-sm font-extrabold transition-colors duration-200 flex items-center gap-1.5"
              :class="billingCycle === 'yearly' ? 'text-orange-500' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
            >
              Hàng năm
              <span class="inline-block px-2 py-0.5 rounded-full bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-wider">
                Tiết kiệm
              </span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
            <div 
              v-for="card in pricingCards" 
              :key="card.title"
              class="rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1 relative border bg-white dark:bg-slate-950/40 hover:border-slate-300 dark:hover:border-white/15 hover:shadow-lg dark:hover:bg-slate-900/60"
              :class="card.isPopular ? 'border-orange-500 shadow-[0_0_25px_rgba(249,115,22,0.15)] ring-1 ring-orange-500/30' : 'border-slate-200 dark:border-white/5'"
            >
              <!-- Popular badge -->
              <span 
                v-if="card.isPopular" 
                class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-[#10b981] text-[9px] font-black text-white uppercase tracking-wider shadow-md"
              >
                Khuyên dùng
              </span>

              <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">{{ card.title }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-6 font-medium">{{ card.subtitle }}</p>
                
                <div class="flex items-baseline flex-wrap gap-1 mb-6">
                  <span class="text-2xl font-black text-orange-500 tracking-tight">
                    {{ billingCycle === 'monthly' ? card.priceMonthly : card.priceYearly }}
                  </span>
                  <div class="flex flex-col ml-1">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold leading-none">
                      {{ billingCycle === 'monthly' ? card.unitMonthly : card.unitYearly }}
                    </span>
                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-medium mt-1">
                      {{ billingCycle === 'monthly' ? 'Hoặc ' + card.priceYearly + ' ' + card.unitYearly : 'Hoặc ' + card.priceMonthly + ' ' + card.unitMonthly }}
                    </span>
                  </div>
                </div>

                <div class="h-px w-full bg-slate-200 dark:bg-white/5 mb-6"></div>

                <ul class="space-y-3 mb-8 text-xs font-semibold text-slate-600 dark:text-slate-300">
                  <li v-for="feat in card.features" :key="feat" class="flex items-start gap-2">
                    <svg class="h-4 w-4 text-emerald-400 stroke-[3] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="leading-tight">{{ feat }}</span>
                  </li>
                </ul>
              </div>

              <div>
                <!-- Subtext badge before CTA button -->
                <div class="bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-white/5 rounded-lg py-2 px-3 text-center text-[10px] text-slate-500 dark:text-slate-400 mb-5 font-semibold">
                  {{ card.metaFee }}
                </div>

                <button 
                  @click="openModalWithPackage(card.packageKey)"
                  class="w-full py-3 rounded-xl text-xs font-extrabold transition-all duration-300 transform active:scale-98"
                  :class="card.isPopular 
                    ? 'bg-orange-500 hover:bg-orange-600 text-white shadow-md shadow-orange-500/20' 
                    : 'border border-orange-500/50 hover:bg-orange-500/10 text-orange-600 dark:text-white'"
                >
                  {{ card.btnText }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Quy trình 4 bước Section -->
      <ProcessSteps />

      <!-- Testimonials Section -->
      <Testimonials />

      <!-- FAQ Accordion Section -->
      <section id="faq" class="py-20 bg-white dark:bg-[#0d1829] relative overflow-hidden border-t border-slate-200 dark:border-white/5 transition-colors duration-300">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
              Đăng Ký Tích Xanh Có An Toàn Không?
            </h2>
            <p class="text-orange-500 text-lg sm:text-xl font-bold tracking-tight">
              — Giải Đáp 8 Câu Hỏi Top
            </p>
          </div>

          <!-- Accordion list -->
          <div class="space-y-4">
            <div 
              v-for="(faq, index) in faqs" 
              :key="index"
              class="rounded-2xl border transition-all duration-300 overflow-hidden bg-slate-50 dark:bg-[#1e2a44]"
              :class="isFaqOpen(index) ? 'border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.10)] dark:shadow-[0_0_20px_rgba(59,130,246,0.15)]' : 'border-slate-200 dark:border-white/5 hover:border-slate-300 dark:hover:border-white/10'"
            >
              <!-- Accordion Header -->
              <button 
                @click="toggleFaq(index)"
                class="w-full flex items-center justify-between p-5 text-left font-bold text-xs sm:text-sm text-slate-900 dark:text-white focus:outline-none select-none transition-colors"
              >
                <span>{{ faq.question }}</span>
                <span 
                  class="flex h-5 w-5 items-center justify-center rounded-full transition-all duration-300"
                  :class="isFaqOpen(index) ? 'bg-orange-500/20 text-orange-500 border border-orange-500/30' : 'bg-slate-200 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 border border-slate-300 dark:border-white/5'"
                >
                  <svg 
                    v-if="isFaqOpen(index)"
                    class="h-2.5 w-2.5" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="3"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                  <span v-else class="text-xs leading-none mt-[-2px]">+</span>
                </span>
              </button>

              <!-- Accordion Body -->
              <div 
                class="transition-all duration-300 ease-in-out overflow-hidden"
                :style="{
                  maxHeight: isFaqOpen(index) ? '200px' : '0px',
                  opacity: isFaqOpen(index) ? 1 : 0
                }"
              >
                <div class="px-5 pb-5 pt-1 text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed border-t border-slate-200 dark:border-white/5">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Bài viết hữu ích (Useful Articles) Section -->
      <section v-if="enableBlog" id="tin-tuc" class="py-20 bg-white dark:bg-[#060b13] relative overflow-hidden border-t border-slate-200 dark:border-white/5 transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Bài viết hữu ích</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-medium">Kiến thức cần biết trước khi đăng ký tích xanh</p>
          </div>

          <!-- Top grid: thin article cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-5xl mx-auto mb-8">
            <a v-for="article in articles" :key="article.id" href="#top" @click="selectArticle(article); scrollToTop();" class="flex items-center justify-between rounded-xl border border-slate-200 dark:border-[#24324f] bg-slate-50 dark:bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-orange-400 dark:hover:border-blue-500/60 hover:bg-orange-50 dark:hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-orange-600 dark:group-hover:text-white transition-colors">{{ article.title }}</span>
              <svg class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500 group-hover:text-orange-500 dark:group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
          </div>

          <!-- Bottom: 4 thick colored service cards (1 row of 4) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <!-- 1. Tích xanh Fanpage (Purple) -->
            <a href="#top" @click="selectService(0); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#8b5cf6] to-[#a855f7] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-purple-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Fanpage</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 2. Tích xanh Facebook cá nhân (Blue) -->
            <a href="#top" @click="selectService(1); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#2563eb] to-[#3b82f6] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-blue-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Facebook</p>
                <p class="text-[10px] font-bold text-blue-100 mt-0.5">cá nhân</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 3. Tích xanh TikTok (Dark Navy) -->
            <a href="#top" @click="selectService(3); scrollToTop();" class="flex items-center justify-between rounded-2xl border border-white/10 bg-gradient-to-r from-[#1e293b] to-[#0f172a] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-slate-800/20 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh TikTok</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 4. Tích xanh Instagram (Pink/Magenta) -->
            <a href="#top" @click="selectService(2); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#db2777] to-[#ec4899] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-pink-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Instagram</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </section>

      <!-- Liên hệ Section -->
      <section id="lien-he" class="py-20 bg-slate-50 dark:bg-[#0d1829] relative overflow-hidden transition-colors duration-300">
        <!-- subtle grid bg -->
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Liên hệ</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-medium">Chúng tôi trả lời trong 15 phút</p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start max-w-5xl mx-auto">
            <!-- Left: contact info cards -->
            <div class="flex flex-col gap-4">
              <!-- Hotline -->
              <a 
                href="tel:0968825068"
                class="flex items-center gap-4 rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#141f36] px-5 py-4 shadow-sm dark:shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-orange-400 dark:hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)] cursor-pointer"
              >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 dark:bg-blue-600/15 border border-orange-400/30 dark:border-blue-500/20">
                  <svg class="h-5 w-5 text-orange-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5">Hotline</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">0968.825.068</p>
                </div>
              </a>

              <!-- Zalo -->
              <a 
                href="https://zalo.me/0968825068"
                target="_blank"
                class="flex items-center gap-4 rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#141f36] px-5 py-4 shadow-sm dark:shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-orange-400 dark:hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)] cursor-pointer"
              >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 dark:bg-blue-600/15 border border-orange-400/30 dark:border-blue-500/20">
                  <svg class="h-5 w-5 text-orange-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.02 2 11c0 2.77 1.28 5.27 3.3 7.02L4 22l4.22-1.4A10.3 10.3 0 0012 21c5.52 0 10-4.02 10-9S17.52 2 12 2zm.92 13.36l-.44-.45c-.36-.37-.77-.56-1.2-.56-.27 0-.55.07-.84.22l-1.45.74c-.1.05-.2.05-.3-.02l-.3-.25c-1.68-1.47-2.8-3.35-2.8-4.6 0-.14.04-.27.12-.38l.88-1.13c.22-.28.27-.6.13-.9L5.6 7.05a.48.48 0 00-.5-.28c-.52.05-1 .3-1.38.7C3.26 8 3 8.88 3 9.87c0 1.3.46 2.66 1.26 3.87 1.17 1.77 2.87 3.23 4.83 4.1.97.44 1.9.66 2.76.66.9 0 1.7-.24 2.3-.68.5-.36.82-.88.9-1.46z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5">Zalo</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">0968.825.068</p>
                </div>
              </a>

              <!-- Email -->
              <a 
                href="mailto:azmedia.com.vn@gmail.com"
                class="flex items-center gap-4 rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#141f36] px-5 py-4 shadow-sm dark:shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-orange-400 dark:hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)] cursor-pointer"
              >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 dark:bg-blue-600/15 border border-orange-400/30 dark:border-blue-500/20">
                  <svg class="h-5 w-5 text-orange-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5">Email</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">azmedia.com.vn@gmail.com</p>
                </div>
              </a>

              <!-- Địa chỉ -->
              <div class="flex items-center gap-4 rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#141f36] px-5 py-4 shadow-sm dark:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 dark:bg-blue-600/15 border border-orange-400/30 dark:border-blue-500/20">
                  <svg class="h-5 w-5 text-orange-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5">Địa Chỉ</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">127 Lê Trọng Tấn, Phường An Khê, Đà Nẵng</p>
                </div>
              </div>

              <!-- Giờ làm việc -->
              <div class="flex items-center gap-4 rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#141f36] px-5 py-4 shadow-sm dark:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 dark:bg-blue-600/15 border border-orange-400/30 dark:border-blue-500/20">
                  <svg class="h-5 w-5 text-orange-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-0.5">Giờ Làm Việc</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">08:00 – 18:00 (T2–T7)</p>
                </div>
              </div>
            </div>

            <!-- Right: registration form -->
            <div class="rounded-2xl border border-slate-200 dark:border-[#24324f] bg-white dark:bg-[#070d1a] p-6 sm:p-8 shadow-sm">
              <template v-if="!contactSubmitted">
                <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white mb-1">Đăng ký tư vấn miễn phí</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Điền 3 thông tin, chúng tôi gọi lại trong 15 phút</p>

                <form @submit.prevent="handleContactSubmit" class="space-y-4">
                  <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-white uppercase tracking-wider mb-1.5">Họ tên <span class="text-orange-500">*</span></label>
                    <input
                      type="text"
                      v-model="contactName"
                      placeholder="Nguyễn Văn A"
                      required
                      class="w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#141f36] px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-[#9aa8c7] transition-colors focus:border-orange-400 dark:focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-orange-400/50 dark:focus:ring-blue-500/50"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-white uppercase tracking-wider mb-1.5">Số điện thoại <span class="text-orange-500">*</span></label>
                    <input
                      type="tel"
                      :value="contactPhone"
                      @keypress="!/[0-9]/.test($event.key) && $event.preventDefault()"
                      @input="onContactPhoneInput"
                      maxlength="11"
                      placeholder="0912345678"
                      required
                      class="w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#141f36] px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-[#9aa8c7] transition-colors focus:border-orange-400 dark:focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-orange-400/50 dark:focus:ring-blue-500/50"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-white uppercase tracking-wider mb-1.5">Lời nhắn <span class="text-orange-500">*</span></label>
                    <textarea
                      v-model="contactNote"
                      placeholder="VD: Tôi muốn đăng ký tích xanh cho Fanpage ABC..."
                      rows="3"
                      class="w-full rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#141f36] px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-[#9aa8c7] transition-colors focus:border-orange-400 dark:focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-orange-400/50 dark:focus:ring-blue-500/50 resize-none"
                    ></textarea>
                  </div>

                  <div class="flex items-center gap-2">
                    <input
                      id="contact-robot"
                      type="checkbox"
                      v-model="contactRobot"
                      class="h-4 w-4 rounded border bg-slate-50 dark:bg-[#141f36] accent-orange-500 cursor-pointer transition-colors"
                      :class="contactRobot ? 'border-orange-500' : 'border-slate-300 dark:border-[#64748b]/40'"
                    />
                    <label for="contact-robot" class="text-xs text-slate-500 dark:text-[#64748b] cursor-pointer select-none">Tôi xác nhận không phải robot</label>
                  </div>

                  <button
                    type="submit"
                    :disabled="contactLoading || !contactRobot"
                    class="w-full rounded-xl bg-orange-500 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition-all duration-300 hover:bg-orange-600 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed"
                  >
                    <span v-if="!contactLoading">Gửi yêu cầu tư vấn</span>
                    <span v-else class="flex items-center justify-center gap-2">
                      <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Đang xử lý...
                    </span>
                  </button>

                  <p class="text-center text-[10px] text-slate-500">Cam kết bảo mật thông tin, không spam</p>
                </form>
              </template>

              <!-- Success state -->
              <template v-else>
                <div class="text-center py-8 space-y-4">
                  <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 mx-auto">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="text-xl font-black text-slate-900 dark:text-white">Gửi thành công!</h3>
                  <p class="text-sm text-slate-600 dark:text-slate-300">Cảm ơn <strong class="text-slate-900 dark:text-white">{{ contactName }}</strong>. Chuyên viên sẽ gọi lại cho bạn trong vòng 15 phút.</p>
                  <button
                    @click="contactSubmitted = false; contactName = ''; contactPhone = ''; contactNote = ''; contactRobot = false;"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 px-6 py-2.5 text-sm font-semibold text-slate-800 dark:text-white transition-colors mt-2"
                  >
                    Gửi yêu cầu khác
                  </button>
                </div>
              </template>
            </div>
          </div>
        </div>
      </section>
    </main>
      </template>
    </template>

    <template v-else-if="enableBlog">
      <ArticleView 
        :article="selectedArticle" 
        :allArticles="articles" 
        @back="clearArticle" 
        @select-article="selectArticle" 
        @open-register="openModal" 
        @select-service="selectService" 
      />
    </template>

    <!-- Footer component -->
    <Footer 
      @open-register="openModal" 
      @open-page="openPage" 
      @select-service="selectService" 
    />

    <!-- Inline Registry Form Modal -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#030712]/85 backdrop-blur-md"
      @click.self="closeModal"
    >
      <div 
        class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#0f172a] shadow-2xl transition-colors duration-300 transform scale-100"
      >
        <!-- Top color bar matching selected service -->
        <div 
          class="h-1.5 w-full bg-blue-600 transition-colors duration-300"
          :class="{
            'bg-blue-600': currentServiceIndex === 0 || currentServiceIndex === 1,
            'bg-pink-600': currentServiceIndex === 2,
            'bg-cyan-500': currentServiceIndex === 3,
            'bg-emerald-500': currentServiceIndex === 4,
          }"
        ></div>

        <!-- Close Button -->
        <button 
          @click="closeModal" 
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 rounded-lg p-1.5 transition-colors focus:outline-none"
        >
          <X class="h-5 w-5" />
        </button>

        <!-- Form Content -->
        <div class="p-6 sm:p-8 text-left">
          
          <div v-if="!isSubmitted">
            <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white mb-2">
              Đăng Ký Tích Xanh
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
              Vui lòng điền thông tin bên dưới, chuyên viên của chúng tôi sẽ liên hệ tư vấn trong 5 - 15 phút.
            </p>

            <form @submit.prevent="handleSubmit" class="space-y-4">
              <!-- Service Selector -->

              <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                  Họ Và Tên
                </label>
                <input 
                  type="text" 
                  v-model="name"
                  placeholder="Nguyễn Văn A"
                  required
                  class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/60 px-4 py-3 text-sm text-slate-900 dark:text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                  Số Điện Thoại
                </label>
                <input 
                  type="tel" 
                  :value="phone"
                  @keypress="!/[0-9]/.test($event.key) && $event.preventDefault()"
                  @input="onPhoneInput"
                  maxlength="11"
                  placeholder="0921169999"
                  required
                  pattern="[0-9]{9,11}"
                  title="Vui lòng nhập số điện thoại hợp lệ từ 9 đến 11 số"
                  class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/60 px-4 py-3 text-sm text-slate-900 dark:text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                  Dịch vụ quan tâm
                </label>
                <select 
                  v-model="formServiceIndex" 
                  required
                  class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/60 px-4 py-3 text-sm text-slate-900 dark:text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                  <option value="" disabled class="text-slate-500 dark:bg-slate-900">-- Chọn dịch vụ --</option>
                  
                  <optgroup label="Gói Meta Verified Doanh Nghiệp" class="text-slate-400 bg-slate-900 font-bold">
                    <option value="package_standard" class="text-slate-900 dark:text-white dark:bg-slate-900 font-medium">Business Standard</option>
                    <option value="package_plus" class="text-slate-900 dark:text-white dark:bg-slate-900 font-medium">Business Plus</option>
                    <option value="package_premium" class="text-slate-900 dark:text-white dark:bg-slate-900 font-medium">Business Premium</option>
                    <option value="package_max" class="text-slate-900 dark:text-white dark:bg-slate-900 font-medium">Business Max (VIP)</option>
                  </optgroup>
                  
                  <optgroup label="Tích Xanh Theo Nền Tảng" class="text-slate-400 bg-slate-900 font-bold">
                    <option 
                      v-for="(service, idx) in services" 
                      :key="service.id" 
                      :value="idx"
                      class="text-slate-900 dark:text-white dark:bg-slate-900 font-medium"
                    >
                      {{ service.name }}
                    </option>
                  </optgroup>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                  Link Trang Cá Nhân / Fanpage
                </label>
                <input 
                  type="text" 
                  v-model="link"
                  placeholder="Ví dụ: facebook.com/dangkytichxanh"
                  required
                  class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-900/60 px-4 py-3 text-sm text-slate-900 dark:text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
              </div>

              <!-- Quick Info Badge -->
              <div class="rounded-xl bg-slate-100 dark:bg-slate-950/40 p-4 border border-slate-200 dark:border-white/5 space-y-2">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                  <Check class="h-4 w-4 text-emerald-500 dark:text-emerald-400 stroke-[3]" />
                  <span>Trọn gói {{ selectedPriceDisplay }}, không phát sinh thêm phí.</span>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                  <Check class="h-4 w-4 text-emerald-500 dark:text-emerald-400 stroke-[3]" />
                  <span>Làm xong mới thanh toán – An toàn 100%.</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit" 
                :disabled="isLoading"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-500 transition-all duration-300 transform active:scale-98 disabled:opacity-70 disabled:cursor-not-allowed"
              >
                <template v-if="!isLoading">
                  <Send class="h-4 w-4" />
                  Gửi Yêu Cầu Đăng Ký
                </template>
                
                <template v-else>
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Đang xử lý...
                </template>
              </button>
            </form>
          </div>

          <!-- Success Screen -->
          <div v-else class="text-center py-6 space-y-4">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 mb-2">
              <CheckCircle2 class="h-10 w-10" />
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
              Đăng Ký Thành Công!
            </h3>
            <p class="text-slate-650 dark:text-slate-300 max-w-sm mx-auto leading-relaxed">
              Cảm ơn <strong class="text-slate-900 dark:text-white">{{ name }}</strong>. Chúng tôi đã nhận được thông tin liên hệ cho dịch vụ <strong class="text-slate-900 dark:text-white">{{ services[currentServiceIndex].name }}</strong>. 
            </p>
            <p class="text-xs text-slate-550 dark:text-slate-400 leading-normal">
              Chuyên viên hỗ trợ tích xanh sẽ liên hệ trực tiếp cho bạn qua số <strong class="text-slate-700 dark:text-slate-200">{{ phone }}</strong> trong vòng 15 phút tới.
            </p>
            <button 
              @click="closeModal" 
              class="inline-flex items-center justify-center rounded-xl bg-slate-200 hover:bg-slate-350 dark:bg-slate-800 dark:hover:bg-slate-700 px-6 py-2.5 text-sm font-semibold text-slate-850 dark:text-white transition-colors mt-4"
            >
              Đóng cửa sổ
            </button>
          </div>

        </div>

      </div>
    </div>

    <!-- Lead Detail Modal (Overlays Admin Page) -->
    <div 
      v-if="isLeadDetailModalOpen && selectedLeadDetail" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
      @click.self="closeLeadDetail"
    >
      <div class="relative w-full max-w-lg rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#070d1a] shadow-2xl overflow-hidden transition-colors duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 p-5">
          <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
            <Shield class="h-5 w-5 text-blue-500" />
            Chi tiết khách hàng đăng ký
          </h3>
          <button 
            @click="closeLeadDetail" 
            class="text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 rounded-lg p-1.5 transition-colors focus:outline-none"
          >
            <X class="h-5 w-5" />
          </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5 text-left text-xs text-slate-650 dark:text-slate-300 max-h-[80vh] overflow-y-auto">
          <!-- Customer Name & Date -->
          <div class="space-y-1">
            <h4 class="text-xl font-black text-slate-900 dark:text-white">{{ selectedLeadDetail.name }}</h4>
            <p class="text-slate-500 font-semibold">
              Đăng ký ngày: {{ new Date(selectedLeadDetail.created_at).toLocaleString('vi-VN', {hour: '2-digit', minute:'2-digit', second: '2-digit', day:'2-digit', month:'2-digit', year:'numeric'}) }}
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-b border-slate-200 dark:border-slate-900/60 py-4">
            <!-- Phone Column -->
            <div class="space-y-2">
              <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Số điện thoại</span>
              <span class="block text-sm font-bold text-slate-900 dark:text-white tracking-wide">{{ selectedLeadDetail.phone }}</span>
              <div class="flex gap-2 pt-1">
                <a 
                  :href="'tel:' + selectedLeadDetail.phone"
                  class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 px-3 py-2 text-[11px] font-bold text-white transition-all shadow-lg active:scale-95"
                >
                  <Phone class="h-3.5 w-3.5" />
                  Gọi điện
                </a>
                <a 
                  :href="'https://zalo.me/' + selectedLeadDetail.phone"
                  target="_blank"
                  class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-650/10 border border-blue-500/20 px-3 py-2 text-[11px] font-bold text-blue-500 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all"
                >
                  <MessageSquare class="h-3.5 w-3.5" />
                  Chat Zalo
                </a>
              </div>
            </div>

            <!-- Service Column -->
            <div class="space-y-2">
              <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dịch vụ đăng ký</span>
              <span class="block text-sm font-bold text-blue-600 dark:text-blue-400">{{ selectedLeadDetail.service }}</span>
              
              <!-- Status Selector inside Modal -->
              <div class="pt-1">
                <span class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cập nhật trạng thái</span>
                <select 
                  v-model="selectedLeadDetail.status" 
                  @change="updateLeadStatus(selectedLeadDetail.id, $event.target.value)"
                  class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-[#0c1524] text-slate-800 dark:text-slate-350 px-2.5 py-1.5 text-xs font-bold focus:outline-none transition-colors"
                  :class="{
                    'text-amber-600 dark:text-amber-400 border-amber-500/30 bg-amber-500/5': selectedLeadDetail.status === 'pending',
                    'text-blue-600 dark:text-blue-400 border-blue-500/30 bg-blue-500/5': selectedLeadDetail.status === 'contacting' || selectedLeadDetail.status === 'contacted',
                    'text-emerald-600 dark:text-emerald-400 border-emerald-500/30 bg-emerald-500/5': selectedLeadDetail.status === 'completed'
                  }"
                >
                  <option value="pending" class="text-slate-900 dark:text-white dark:bg-slate-950">Chờ xử lý (Pending)</option>
                  <option value="contacting" class="text-slate-900 dark:text-white dark:bg-slate-950">Đang liên hệ (Contacting)</option>
                  <option value="completed" class="text-slate-900 dark:text-white dark:bg-slate-950">Đã hoàn thành (Completed)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Account link details -->
          <div class="space-y-2">
            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tài khoản / Link đăng ký</span>
            <div class="rounded-xl border border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-[#0c1524] p-3 flex items-center justify-between gap-3">
              <span class="text-xs text-slate-800 dark:text-slate-300 break-all select-all font-mono leading-relaxed">{{ selectedLeadDetail.link }}</span>
              <a 
                :href="selectedLeadDetail.link" 
                target="_blank"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-500 hover:text-white transition-all shrink-0"
                title="Mở đường dẫn mới"
              >
                <ExternalLink class="h-4 w-4" />
              </a>
            </div>
          </div>

          <!-- Message details -->
          <div class="space-y-2">
            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Lời nhắn của khách</span>
            <div class="rounded-xl border border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-[#0c1524] p-3 text-slate-700 dark:text-slate-400 whitespace-pre-wrap leading-relaxed">
              {{ selectedLeadDetail.message || 'Không có lời nhắn.' }}
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-900/40">
          <button 
            @click="closeLeadDetail"
            class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-[#0c1524] px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"
          >
            Đóng cửa sổ
          </button>
        </div>
      </div>
    </div>

    <!-- Social Proof Notification (Bottom-Left) -->
    <div 
      class="fixed bottom-6 left-6 z-40 max-w-[320px] rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-[#0c1524]/90 backdrop-blur-xl p-4 shadow-2xl transition-all duration-500 flex items-center gap-3"
      :class="showSocialProof && socialProofData ? 'translate-x-0 opacity-100 scale-100' : '-translate-x-12 opacity-0 scale-95 pointer-events-none'"
    >
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-500/20">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs text-slate-900 dark:text-white font-extrabold truncate">
          {{ socialProofData?.name }} <span class="text-slate-500 dark:text-slate-400 font-semibold">({{ socialProofData?.location }})</span>
        </p>
        <p class="text-[11px] text-slate-700 dark:text-slate-300 font-medium mt-0.5 leading-snug">
          Vừa đăng ký: <strong class="text-orange-600 dark:text-orange-400">{{ socialProofData?.service }}</strong>
        </p>
        <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold mt-1 uppercase tracking-wider">
          {{ socialProofData?.time }}
        </p>
      </div>
      <button 
        @click="showSocialProof = false" 
        class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 self-start p-0.5"
      >
        <X class="h-3 w-3" />
      </button>
    </div>

    <!-- Floating Contact Actions & Chat Toggle (Bottom-Right) -->
    <div class="fixed bottom-6 right-6 z-40 flex flex-col items-end gap-3 pointer-events-none">
      
      <!-- Hotline floating bubble -->
      <a 
        href="tel:0968825068"
        class="pointer-events-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-600 text-white shadow-xl hover:bg-rose-500 transition-all hover:scale-110 relative group"
        title="Gọi điện hotline: 0968.825.068"
      >
        <Phone class="h-5 w-5 animate-pulse" />
        <span class="absolute right-14 bg-[#0c1524] border border-slate-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
          Hotline: 0968.825.068
        </span>
        <!-- Pulsing ring effect -->
        <span class="absolute -inset-1 rounded-full border border-rose-500/30 animate-ping opacity-75"></span>
      </a>

      <!-- Zalo floating bubble -->
      <a 
        href="https://zalo.me/0968825068"
        target="_blank"
        class="pointer-events-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#0068ff] text-white shadow-xl hover:bg-[#1a7aff] transition-all hover:scale-110 relative group"
        title="Chat Zalo hỗ trợ"
      >
        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.02 2 11c0 2.77 1.28 5.27 3.3 7.02L4 22l4.22-1.4A10.3 10.3 0 0012 21c5.52 0 10-4.02 10-9S17.52 2 12 2zm.92 13.36l-.44-.45c-.36-.37-.77-.56-1.2-.56-.27 0-.55.07-.84.22l-1.45.74c-.1.05-.2.05-.3-.02l-.3-.25c-1.68-1.47-2.8-3.35-2.8-4.6 0-.14.04-.27.12-.38l.88-1.13c.22-.28.27-.6.13-.9L5.6 7.05a.48.48 0 00-.5-.28c-.52.05-1 .3-1.38.7C3.26 8 3 8.88 3 9.87c0 1.3.46 2.66 1.26 3.87 1.17 1.77 2.87 3.23 4.83 4.1.97.44 1.9.66 2.76.66.9 0 1.7-.24 2.3-.68.5-.36.82-.88.9-1.46z"/>
        </svg>
        <span class="absolute right-14 bg-[#0c1524] border border-slate-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
          Chat Zalo qua sđt
        </span>
      </a>

      <!-- Facebook floating bubble -->
      <a 
        href="https://fb.com/congtyazmedia"
        target="_blank"
        class="pointer-events-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#1877f2] text-white shadow-xl hover:bg-[#3b82f6] transition-all hover:scale-110 relative group"
        title="Trang Facebook Công ty"
      >
        <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
          <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
        </svg>
        <span class="absolute right-14 bg-[#0c1524] border border-slate-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
          Facebook Công ty
        </span>
      </a>

      <!-- Chat toggle floating bubble -->
      <button 
        @click="toggleCustomerChat"
        class="pointer-events-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-2xl hover:bg-blue-500 transition-all hover:scale-115 relative"
        :class="isCustomerChatOpen ? 'rotate-90 bg-slate-800 hover:bg-slate-700' : ''"
        title="Hỗ trợ trực tuyến"
      >
        <X v-if="isCustomerChatOpen" class="h-6 w-6" />
        <MessageSquare v-else class="h-6 w-6" />

        <!-- Unread badge -->
        <span 
          v-if="!isCustomerChatOpen && unreadCustomerMessagesCount > 0"
          class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-rose-600 text-[10px] font-black text-white border-2 border-[#030712] animate-bounce"
        >
          {{ unreadCustomerMessagesCount }}
        </span>
      </button>

    </div>

    <!-- Customer Chat Window Panel -->
    <div 
      class="fixed bottom-24 right-6 z-50 w-[350px] max-w-[calc(100vw-2rem)] h-[480px] rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-[#0c1524]/95 backdrop-blur-xl shadow-2xl flex flex-col overflow-hidden transition-all duration-300 transform"
      :class="isCustomerChatOpen ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-10 scale-90 pointer-events-none'"
    >
      <!-- Chat Header -->
      <div class="p-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between shadow-md">
        <div class="flex items-center gap-2">
          <div class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></div>
          <div>
            <h4 class="text-xs font-black">Hỗ Trợ Trực Tuyến</h4>
            <p class="text-[9px] text-blue-100 font-semibold mt-0.5">Chúng tôi online phản hồi ngay</p>
          </div>
        </div>
        <button @click="isCustomerChatOpen = false" class="text-white/80 hover:text-white transition-colors">
          <X class="h-4 w-4" />
        </button>
      </div>

      <!-- Chat Body -->
      <div class="flex-1 flex flex-col min-h-0 bg-slate-50/40 dark:bg-[#060b13]/40">
        
        <!-- Welcome / Input Name Screen -->
        <div v-if="!customerNameEntered" class="flex-1 flex flex-col justify-center p-6 text-center space-y-4">
          <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
            <MessageSquare class="h-6 w-6" />
          </div>
          <div>
            <h5 class="text-xs font-bold text-slate-900 dark:text-white">Bắt Đầu Trò Chuyện</h5>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Vui lòng nhập tên của bạn để nhân viên tiện tư vấn hỗ trợ đăng ký tích xanh.</p>
          </div>
          
          <form @submit.prevent="enterCustomerName" class="space-y-3">
            <input 
              type="text" 
              v-model="customerNameInput"
              placeholder="Tên của bạn..."
              required
              class="w-full rounded-xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
            />
            <button 
              type="submit"
              class="w-full rounded-xl bg-blue-600 hover:bg-blue-500 py-2.5 text-xs font-bold text-white transition-all"
            >
              Kết nối tư vấn viên
            </button>
          </form>
        </div>

        <!-- Messages Thread Screen -->
        <template v-else>
          <!-- Messages list -->
          <div 
            id="customer-chat-messages" 
            class="flex-1 p-4 overflow-y-auto space-y-3"
          >
            <div class="text-center py-2">
              <span class="inline-block bg-slate-100 dark:bg-slate-900/60 text-[9px] text-slate-600 dark:text-slate-500 px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">
                Kết nối thành công với: {{ customerNameEntered }}
              </span>
            </div>
            
            <div v-if="customerChatMessages.length === 0" class="text-center text-slate-500 py-4 text-[10px] italic">
              Nhập câu hỏi của bạn bên dưới để nhận hỗ trợ...
            </div>

            <div 
              v-for="msg in customerChatMessages" 
              :key="msg.id"
              class="flex flex-col max-w-[80%]"
              :class="msg.sender_type === 'customer' ? 'ml-auto items-end' : 'items-start'"
            >
              <span class="text-[8px] text-slate-500 mb-0.5 px-1 font-semibold">
                {{ msg.sender_type === 'customer' ? 'Bạn' : 'Tư vấn viên (' + (msg.sender_name || 'Hỗ trợ') + ')' }} • {{ new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'}) }}
              </span>
              <div 
                class="rounded-2xl px-3 py-2 text-xs text-left"
                :class="msg.sender_type === 'customer' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-transparent rounded-tl-none'"
              >
                {{ msg.message }}
              </div>
            </div>
          </div>

          <!-- Input area -->
          <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-[#0c1524]/60">
            <form @submit.prevent="sendCustomerMessage" class="flex gap-2">
              <input 
                type="text" 
                v-model="customerChatInput"
                placeholder="Nhập câu hỏi của bạn..."
                required
                class="flex-1 rounded-xl border border-slate-350 dark:border-slate-800 bg-white dark:bg-[#060b13] px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-450 dark:placeholder-slate-500 focus:border-blue-500 focus:outline-none transition-colors"
              />
              <button 
                type="submit"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white hover:bg-blue-500 transition-all active:scale-95 flex-shrink-0"
              >
                <Send class="h-3.5 w-3.5" />
              </button>
            </form>
          </div>
        </template>

      </div>
    </div>
  </div>
</template>
