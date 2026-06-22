<script setup>
import { computed, onMounted, ref } from 'vue';
import {
  Award,
  BadgeCheck,
  BarChart3,
  Check,
  ChevronDown,
  Clock,
  LockKeyhole,
  Mail,
  MapPin,
  Menu,
  MessageCircle,
  Moon,
  Phone,
  ShieldCheck,
  Star,
  Sun,
  TrendingUp,
  Trophy,
  WalletCards
} from '@lucide/vue';
import { articles as articlePosts } from './data/articles';

const isDark = ref(false);
const activeFaq = ref(0);
const leadName = ref('');
const leadPhone = ref('');
const leadLink = ref('');
const selectedArticle = ref(null);
const selectedHeroService = ref(null);
const selectedPage = ref(null); // 'blog' | 'gioi-thieu' | 'legal-terms' | 'legal-privacy' | 'legal-dmca'
const formSuccess = ref(false);
const formError = ref(false);
const formLoading = ref(false);

// ΓöÇΓöÇ SEO HELPERS ΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇ
const SITE = '─É─âng K├╜ T├¡ch Xanh';
const DEFAULT_DESC = '─É─âng k├╜ t├¡ch xanh Facebook, Instagram 15 ph├║t. Kh├┤ng cß║ºn b├ío ch├¡, l├ám xong thanh to├ín. 100% th├ánh c├┤ng, 2.368 kh├ích. Hotline 0968.825.068.';

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
  const fullTitle = title ? `${title} | ${SITE}` : `─É─âng K├╜ T├¡ch Xanh Fanpage, Instagram 15 Ph├║t | ${SITE}`;
  const desc = description || DEFAULT_DESC;
  document.title = fullTitle;
  setMeta('description', desc);
  setMeta('og:title', fullTitle, 'property');
  setMeta('og:description', desc, 'property');
  setMeta('og:url', window.location.href, 'property');
  setMeta('og:type', 'website', 'property');
  setLink('canonical', window.location.href);
};
// ΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇ

const applyTheme = (dark) => {
  isDark.value = dark;
  document.documentElement.classList.toggle('dark', dark);
  localStorage.theme = dark ? 'dark' : 'light';
};

const openArticle = (article) => {
  selectedArticle.value = article;
  selectedPage.value = null;
  selectedHeroService.value = null;
  window.history.pushState({}, '', `/tin-tuc/${article.id}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });
  updateMeta(article.title, article.description);
};

const closeArticle = () => {
  selectedArticle.value = null;
  window.history.pushState({}, '', '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
  updateMeta();
};

const openPage = (page) => {
  selectedArticle.value = null;
  selectedHeroService.value = null;
  selectedPage.value = page;
  const urlMap = { blog: '/blog', 'gioi-thieu': '/gioi-thieu', 'legal-terms': '/legal/terms', 'legal-privacy': '/legal/privacy', 'legal-dmca': '/legal/dmca' };
  const metaMap = {
    blog: ['Tin Tß╗⌐c & Kiß║┐n Thß╗⌐c T├¡ch Xanh 2026', 'Kiß║┐n thß╗⌐c, h╞░ß╗¢ng dß║½n v├á tin tß╗⌐c mß╗¢i nhß║Ñt vß╗ü ─æ─âng k├╜ t├¡ch xanh Facebook, Instagram, TikTok, WhatsApp.'],
    'gioi-thieu': ['Giß╗¢i Thiß╗çu AZ Media', '─Éß╗Öi ng┼⌐ ─É─âng K├╜ T├¡ch Xanh vß╗¢i 5+ n─âm kinh nghiß╗çm Meta Verified ΓÇö ─æ├ú hß╗ù trß╗ú 2.368+ kh├ích h├áng. Cam kß║┐t l├ám xong mß╗¢i thanh to├ín.'],
    'legal-terms': ['─Éiß╗üu Khoß║ún Dß╗ïch Vß╗Ñ', DEFAULT_DESC],
    'legal-privacy': ['Ch├¡nh S├ích Bß║úo Mß║¡t', DEFAULT_DESC],
    'legal-dmca': ['DMCA', DEFAULT_DESC],
  };
  window.history.pushState({}, '', urlMap[page] || '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
  const [t, d] = metaMap[page] || [];
  updateMeta(t, d);
};

const closePage = () => {
  selectedPage.value = null;
  window.history.pushState({}, '', '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
  updateMeta();
};

const submitLead = async () => {
  if (!leadName.value || !leadPhone.value) { formError.value = true; return; }
  formLoading.value = true; formError.value = false;
  try {
    await fetch('https://formspree.io/f/xkgwboqe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ name: leadName.value, phone: leadPhone.value, message: leadLink.value })
    });
    formSuccess.value = true;
    leadName.value = ''; leadPhone.value = ''; leadLink.value = '';
  } catch {
    formError.value = true;
  } finally {
    formLoading.value = false;
  }
};

const defaultHero = {
  title: 'T├¡ch Xanh Fanpage Instagram - Kh├┤ng Cß║ºn B├ío Ch├¡',
  desc: '─É─âng K├╜ T├¡ch Xanh Fanpage, T├¡ch Xanh Instagram ho├án th├ánh sau 15 ph├║t. L├¬n t├¡ch ngay.',
  badge: 'Dß╗ïch Vß╗Ñ Uy T├¡n #1 Viß╗çt Nam',
  features: [
    '├üp dß╗Ñng ch├¡nh s├ích mß╗¢i nhß║Ñt Meta Verified',
    'T├¬n t├ái khoß║ún c├│ t├¡ch xanh, kh├┤ng mß║Ñt khi ─æß╗òi t├¬n',
    'Thanh to├ín khi c├│ t├¡ch xanh, kh├┤ng mß║Ñt tiß╗ün oan',
    '100% th├ánh c├┤ng, 2.368 kh├ích tin t╞░ß╗ƒng'
  ]
};

const servicePages = [
  {
    id: 'dang-ky-tich-xanh-fanpage',
    name: 'T├¡ch xanh Fanpage',
    title: 'Dß╗ïch vß╗Ñ ─æ─âng k├╜ T├¡ch Xanh Fanpage',
    metaTitle: '─É─âng K├╜ T├¡ch Xanh Fanpage 15 Ph├║t | 1.5 Triß╗çu',
    metaDesc: '─É─âng k├╜ t├¡ch xanh Fanpage chß╗ë 15 ph├║t, 1.5 triß╗çu trß╗ìn g├│i. T├¬n Fanpage n├áo c┼⌐ng ─æ╞░ß╗úc. L├ám xong thanh to├ín. Hotline 0968.825.068.',
    desc: 'Hß╗ù trß╗ú kiß╗âm tra v├á ─æ─âng k├╜ t├¡ch xanh cho Fanpage b├ín h├áng, th╞░╞íng hiß╗çu, cß╗Öng ─æß╗ông. L├ám xong mß╗¢i thanh to├ín, t╞░ vß║Ñn r├╡ ─æiß╗üu kiß╗çn tr╞░ß╗¢c khi triß╗ân khai.',
    features: ['Kh├┤ng cß║ºn b├ío ch├¡ trong nhiß╗üu tr╞░ß╗¥ng hß╗úp', 'T─âng ─æß╗Ö tin cß║¡y khi kh├ích h├áng t├¼m kiß║┐m', 'Hß║ín chß║┐ giß║ú mß║ío th╞░╞íng hiß╗çu', 'Ph├╣ hß╗úp shop online v├á doanh nghiß╗çp'],
    price: '1.500.000─æ',
    meta: 'Ph├¡ Meta Verified ─æ╞░ß╗úc t╞░ vß║Ñn theo tß╗½ng hß╗ô s╞í'
  },
  {
    id: 'dang-ky-tich-xanh-instagram',
    name: 'T├¡ch xanh Instagram',
    title: 'Dß╗ïch vß╗Ñ ─æ─âng k├╜ T├¡ch Xanh Instagram',
    metaTitle: '─É─âng K├╜ T├¡ch Xanh Instagram ΓÇô 15 Ph├║t, 1.5 Triß╗çu',
    metaDesc: 'T├¡ch xanh Instagram chß╗ë 15 ph├║t, 1.5 triß╗çu trß╗ìn g├│i. Kh├┤ng cß║ºn b├ío ch├¡, 99% th├ánh c├┤ng. Gß╗ìi 0968.825.068 t╞░ vß║Ñn miß╗àn ph├¡.',
    desc: 'D├ánh cho c├í nh├ón, creator, shop online v├á business cß║ºn t─âng uy t├¡n t├ái khoß║ún Instagram khi b├ín h├áng hoß║╖c x├óy dß╗▒ng th╞░╞íng hiß╗çu.',
    features: ['Kiß╗âm tra ─æiß╗üu kiß╗çn tr╞░ß╗¢c khi l├ám', 'Tß╗æi ╞░u hß╗ô s╞í Instagram', 'Hß╗ù trß╗ú shop v├á creator', 'Theo s├ít tß╗¢i khi c├│ kß║┐t quß║ú'],
    price: '1.500.000─æ',
    meta: 'T╞░ vß║Ñn ph├¡ nß╗ün tß║úng nß║┐u c├│'
  },
  {
    id: 'dang-ky-tich-xanh-tiktok',
    name: 'T├¡ch xanh TikTok',
    title: 'Dß╗ïch vß╗Ñ ─æ─âng k├╜ T├¡ch Xanh TikTok',
    metaTitle: '─É─âng K├╜ T├¡ch Xanh TikTok | T╞░ Vß║Ñn Miß╗àn Ph├¡',
    metaDesc: 'T╞░ vß║Ñn v├á hß╗ù trß╗ú ─æ─âng k├╜ t├¡ch xanh TikTok cho creator, th╞░╞íng hiß╗çu, shop. B├ío ph├¡ tr╞░ß╗¢c khi l├ám. Hotline 0968.825.068.',
    desc: 'T╞░ vß║Ñn v├á hß╗ù trß╗ú hß╗ô s╞í x├íc minh TikTok cho creator, th╞░╞íng hiß╗çu, shop v├á ng╞░ß╗¥i b├ín h├áng cß║ºn t─âng ─æß╗Ö tin cß║¡y.',
    features: ['R├á so├ít hß╗ô s╞í TikTok', 'T╞░ vß║Ñn ─æiß╗üu kiß╗çn x├íc minh', 'Hß╗ù trß╗ú ─æß╗ïnh vß╗ï th╞░╞íng hiß╗çu', 'Cß║¡p nhß║¡t theo ch├¡nh s├ích nß╗ün tß║úng'],
    price: 'T╞░ vß║Ñn theo hß╗ô s╞í',
    meta: 'B├ío r├╡ chi ph├¡ tr╞░ß╗¢c khi triß╗ân khai'
  },
  {
    id: 'dang-ky-tich-xanh-ca-nhan',
    name: 'T├¡ch xanh Facebook c├í nh├ón',
    title: 'Dß╗ïch vß╗Ñ ─æ─âng k├╜ T├¡ch Xanh Facebook c├í nh├ón',
    metaTitle: '─É─âng K├╜ T├¡ch Xanh Facebook C├í Nh├ón | 1.5 Triß╗çu',
    metaDesc: 'Hß╗ù trß╗ú c├í nh├ón, KOL ─æ─âng k├╜ t├¡ch xanh Facebook. T╞░ vß║Ñn miß╗àn ph├¡, kh├┤ng y├¬u cß║ºu mß║¡t khß║⌐u. Hotline 0968.825.068.',
    desc: 'Hß╗ù trß╗ú c├í nh├ón, KOL, chß╗º shop v├á ng╞░ß╗¥i x├óy dß╗▒ng th╞░╞íng hiß╗çu c├í nh├ón ─æ─âng k├╜ x├íc minh t├ái khoß║ún Facebook.',
    features: ['Tß╗æi ╞░u t├¬n v├á hß╗ô s╞í c├í nh├ón', 'T╞░ vß║Ñn giß║Ñy tß╗¥ cß║ºn chuß║⌐n bß╗ï', 'Kh├┤ng y├¬u cß║ºu mß║¡t khß║⌐u', 'Hß╗ù trß╗ú sau khi l├¬n t├¡ch xanh'],
    price: '1.500.000─æ',
    meta: 'Chi ph├¡ nß╗ün tß║úng ─æ╞░ß╗úc t╞░ vß║Ñn ri├¬ng'
  },
  {
    id: 'dang-ky-tich-xanh-whatsapp',
    name: 'T├¡ch xanh WhatsApp',
    title: 'Dß╗ïch vß╗Ñ ─æ─âng k├╜ T├¡ch Xanh WhatsApp',
    metaTitle: '─É─âng K├╜ T├¡ch Xanh WhatsApp Business | T╞░ Vß║Ñn Miß╗àn Ph├¡',
    metaDesc: 'Hß╗ù trß╗ú doanh nghiß╗çp x├íc minh t├ái khoß║ún WhatsApp Business. T─âng ─æß╗Ö tin cß║¡y, ch─âm s├│c kh├ích h├áng hiß╗çu quß║ú. Hotline 0968.825.068.',
    desc: 'D├ánh cho doanh nghiß╗çp d├╣ng WhatsApp Business cß║ºn x├íc minh ch├¡nh chß╗º, t─âng ─æß╗Ö tin cß║¡y khi ch─âm s├│c kh├ích h├áng.',
    features: ['Kiß╗âm tra t├ái khoß║ún doanh nghiß╗çp', 'T╞░ vß║Ñn hß╗ô s╞í x├íc minh', 'Ph├╣ hß╗úp ─æß╗Öi sale/ch─âm s├│c kh├ích h├áng', 'Hß╗ù trß╗ú cß║Ñu h├¼nh th├┤ng tin doanh nghiß╗çp'],
    price: 'T╞░ vß║Ñn theo hß╗ô s╞í',
    meta: 'B├ío ph├¡ sau khi kiß╗âm tra t├ái khoß║ún'
  }
];

const openServicePage = (service) => {
  selectedArticle.value = null;
  selectedPage.value = null;
  selectedHeroService.value = service;
  window.history.pushState({}, '', `/${service.id}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });
  updateMeta(service.metaTitle || service.title, service.metaDesc);
};

const closeServicePage = () => {
  selectedHeroService.value = null;
  window.history.pushState({}, '', '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
  updateMeta();
};

const currentHero = computed(() => selectedHeroService.value ? { ...defaultHero, ...selectedHeroService.value } : defaultHero);

onMounted(() => {
  const savedTheme = localStorage.theme;
  const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
  applyTheme(savedTheme ? savedTheme === 'dark' : prefersDark);
  const path = window.location.pathname;
  // Service pages
  const svc = servicePages.find(s => `/${s.id}` === path);
  if (svc) { selectedHeroService.value = svc; updateMeta(svc.metaTitle || svc.title, svc.metaDesc); return; }
  // Article
  if (path.startsWith('/tin-tuc/')) {
    const id = path.split('/').pop();
    const art = articlePosts.find(a => a.id === id) || null;
    selectedArticle.value = art;
    if (art) updateMeta(art.title, art.description);
    return;
  }
  // Static pages
  const pageMap = {
    '/blog': ['blog', 'Tin Tß╗⌐c & Kiß║┐n Thß╗⌐c T├¡ch Xanh 2026', 'Kiß║┐n thß╗⌐c, h╞░ß╗¢ng dß║½n v├á tin tß╗⌐c mß╗¢i nhß║Ñt vß╗ü ─æ─âng k├╜ t├¡ch xanh Facebook, Instagram, TikTok, WhatsApp.'],
    '/gioi-thieu': ['gioi-thieu', 'Giß╗¢i Thiß╗çu AZ Media', '─Éß╗Öi ng┼⌐ ─É─âng K├╜ T├¡ch Xanh vß╗¢i 5+ n─âm kinh nghiß╗çm Meta Verified ΓÇö ─æ├ú hß╗ù trß╗ú 2.368+ kh├ích h├áng.'],
    '/legal/terms': ['legal-terms', '─Éiß╗üu Khoß║ún Dß╗ïch Vß╗Ñ', DEFAULT_DESC],
    '/legal/privacy': ['legal-privacy', 'Ch├¡nh S├ích Bß║úo Mß║¡t', DEFAULT_DESC],
    '/legal/dmca': ['legal-dmca', 'DMCA', DEFAULT_DESC],
  };
  if (pageMap[path]) {
    const [page, t, d] = pageMap[path];
    selectedPage.value = page;
    updateMeta(t, d);
    return;
  }
  // Default
  updateMeta();
});

const stats = [
  ['2.368', 'Trang ─æ├ú cß║Ñp t├¡ch xanh'],
  ["15'", 'Thß╗¥i gian trung b├¼nh'],
  ['100%', 'Tß╗ë lß╗ç th├ánh c├┤ng'],
  ['1.5tr', 'Trß╗ìn g├│i']
];

const benefits = [
  [ShieldCheck, 'X├íc minh ch├¡nh chß╗º', 'Kh├ích h├áng c├▓n lß║íi sß╗▒ tin t╞░ß╗ƒng, kh├┤ng nghi ngß╗¥ giß║ú mß║ío'],
  [TrendingUp, '╞»u ti├¬n hiß╗ân thß╗ï', 'L╞░ß╗út tiß║┐p cß║¡n tß╗▒ nhi├¬n t─âng 20-40%, hiß╗ân thß╗ï top khi t├¼m kiß║┐m'],
  [BarChart3, 'T─âng doanh thu', 'Tß╗ë lß╗ç chß╗æt ─æ╞ín tr├¬n page t├¡ch xanh t─âng trung b├¼nh 28%'],
  [BadgeCheck, 'Bß║úo vß╗ç th╞░╞íng hiß╗çu', 'Facebook tß╗▒ ─æß╗Öng gß╗í c├íc trang mß║ío danh bß║ín']
];

const priceCards = [
  {
    name: 'T├¡ch Xanh Fanpage',
    desc: 'Cho Fanpage Facebook',
    price: '1.500.000─æ',
    features: ['T├¬n Fanpage n├áo c┼⌐ng l├¬n ─æ╞░ß╗úc', 'Kh├┤ng cß║ºn b├ío ch├¡', '15 ph├║t ho├án th├ánh', 'Bß║úo h├ánh trß╗ìn ─æß╗¥i'],
    button: '─É─âng k├╜ Fanpage'
  },
  {
name: 'T├¡ch Xanh Instagram',
    desc: 'Cho Instagram c├í nh├ón & business',
    price: '1.500.000─æ',
    features: ['C├í nh├ón & doanh nghiß╗çp ─æß╗üu ─æ╞░ß╗úc', 'T─âng ─æß╗Ö tin cß║¡y cho shop online', '15 ph├║t ho├án th├ánh', 'Hß╗ù trß╗ú 24/7'],
    button: '─É─âng k├╜ Instagram'
  },
  {
    name: 'Combo Fanpage + IG',
    desc: '2 nß╗ün tß║úng, 1 lß║ºn thanh to├ín',
    price: '2.500.000─æ',
    popular: true,
    features: ['─É─âng k├╜ cß║ú Fanpage + Instagram', 'Tiß║┐t kiß╗çm h╞ín ─æ─âng k├╜ ri├¬ng', 'Khuyß║┐n d├╣ng cho shop lß╗¢n', 'Bß║úo h├ánh trß╗ìn ─æß╗¥i'],
    button: '─É─âng k├╜ Combo'
  }
];

const process = [
  ['1', 'Li├¬n hß╗ç t╞░ vß║Ñn', 'Gß╗ìi hoß║╖c nhß║»n Zalo ─æß╗â ─æ╞░ß╗úc t╞░ vß║Ñn miß╗àn ph├¡ trong 5 ph├║t'],
  ['2', 'Cung cß║Ñp th├┤ng tin', 'Gß╗¡i link Fanpage + ß║únh CMND/CCCD qua Zalo'],
  ['3', 'Ch├║ng t├┤i xß╗¡ l├╜', '─Éß╗Öi ng┼⌐ kß╗╣ thuß║¡t ho├án tß║Ñt trong 15-60 ph├║t'],
  ['4', 'Nhß║¡n t├¡ch xanh & thanh to├ín', 'Xem t├¡ch xanh live tr├¬n Fanpage, thanh to├ín khi h├ái l├▓ng']
];

const reasons = [
  [Award, 'Nhanh nhß║Ñt thß╗ï tr╞░ß╗¥ng', '15 ph├║t, kh├┤ng phß║úi 1-7 ng├áy nh╞░ tß╗▒ l├ám'],
  [BadgeCheck, 'Tß╗ë lß╗ç 100% th├ánh c├┤ng', 'L├ám xong mß╗¢i thanh to├ín, kh├┤ng mß║Ñt tiß╗ün oan'],
  [WalletCards, 'Hß╗ù trß╗ú trß╗ìn ─æß╗¥i', 'Gß║╖p sß╗▒ cß╗æ g├¼ ch├║ng t├┤i xß╗¡ l├╜ miß╗àn ph├¡'],
  [LockKeyhole, 'An to├án 100%', 'Chß╗ë d├╣ng ph╞░╞íng ph├íp ch├¡nh thß╗⌐c cß╗ºa Meta'],
  [Phone, 'T╞░ vß║Ñn 24/7', 'Lu├┤n c├│ nh├ón vi├¬n trß╗▒c hotline v├á Zalo'],
  [Trophy, 'Uy t├¡n AZ Media', '─É╞░ß╗úc nhiß╗üu c├í nh├ón v├á doanh nghiß╗çp tin t╞░ß╗ƒng']
];

const reviews = [
  ['Nguyß╗àn V─ân Minh', 'Chß╗º shop thß╗¥i trang', 'NV', 'Sau khi c├│ t├¡ch xanh, inbox hß╗Åi mua t─âng gß║ºn gß║Ñp ─æ├┤i. Kh├ích kh├┤ng c├▓n hß╗Åi "c├│ phß║úi shop thß║¡t kh├┤ng?" nß╗»a.'],
  ['Trß║ºn Thß╗ï Lan', 'Chß╗º spa l├ám ─æß║╣p', 'TT', 'Thß╗º tß╗Ñc cß╗▒c nhanh, ─æ├║ng 20 ph├║t l├á c├│ t├¡ch xanh. Quan trß╗ìng l├á kh├┤ng phß║úi trß║ú tiß╗ün tr╞░ß╗¢c.'],
  ['Phß║ím Hß╗ông Qu├ón', 'KOL kinh doanh', 'PH', 'Nhß╗¥ c├│ t├¡ch xanh m├á nh├ún h├áng chß╗ïu booking gi├í cao h╞ín tr╞░ß╗¢c. ROI qu├í hß╗¥i.']
];

const faqs = [
  ['─É─âng k├╜ t├¡ch xanh mß║Ñt bao l├óu?', 'Chß╗ë 15-60 ph├║t sau khi nhß║¡n ─æß╗º th├┤ng tin. Tr╞░ß╗¥ng hß╗úp ─æß║╖c biß╗çt c├│ thß╗â l├¬n tß╗¢i 24h, ch├║ng t├┤i sß║╜ b├ío tr╞░ß╗¢c.'],
  ['Fanpage mß╗¢i tß║ío c├│ ─æ─âng k├╜ ─æ╞░ß╗úc kh├┤ng?', 'C├│ thß╗â kiß╗âm tra tr╞░ß╗¢c. Ch├║ng t├┤i sß║╜ t╞░ vß║Ñn ─æiß╗üu kiß╗çn cß╗Ñ thß╗â ─æß╗â tr├ính mß║Ñt thß╗¥i gian.'],
  ['Chi ph├¡ ─æ─âng k├╜ cß╗Ñ thß╗â l├á bao nhi├¬u?', 'G├│i phß╗ò biß║┐n tß╗½ 1.500.000─æ, l├ám xong mß╗¢i thanh to├ín.'],
  ['Phß║úi trß║ú ph├¡ trong giai ─æoß║ín hay g├│i th├íng?', 'Ph├¡ dß╗ïch vß╗Ñ ─æ╞░ß╗úc b├ío r├╡ tr╞░ß╗¢c khi l├ám. Nß║┐u c├│ ph├¡ nß╗ün tß║úng tß╗½ Meta, chuy├¬n vi├¬n sß║╜ t╞░ vß║Ñn ri├¬ng.'],
  ['T├¡ch xanh c├│ bß╗ï mß║Ñt kh├┤ng?', 'Ch├║ng t├┤i hß╗ù trß╗ú ─æ├║ng quy tr├¼nh v├á t╞░ vß║Ñn c├ích giß╗» t├ái khoß║ún an to├án sau khi l├¬n t├¡ch xanh.'],
  ['C├│ an to├án kh├┤ng? T├ái khoß║ún c├│ bß╗ï kh├│a kh├┤ng?', 'Quy tr├¼nh kh├┤ng y├¬u cß║ºu mß║¡t khß║⌐u v├á ╞░u ti├¬n ph╞░╞íng ph├íp ch├¡nh thß╗⌐c, minh bß║ích.'],
  ['T├¡ch xanh kh├íc t├¡ch x├ím nh╞░ thß║┐ n├áo?', 'T├¡ch xanh thß╗â hiß╗çn x├íc minh ch├¡nh chß╗º, ph├╣ hß╗úp th╞░╞íng hiß╗çu, c├í nh├ón, shop v├á doanh nghiß╗çp.'],
  ['Nß║┐u kh├┤ng th├ánh c├┤ng th├¼ sao?', 'Kh├┤ng th├ánh c├┤ng th├¼ kh├┤ng thu ph├¡ dß╗ïch vß╗Ñ.']
];
const articles = articlePosts.slice(0, 6);

const quickServices = [
  ['T├¡ch xanh Fanpage', 'from-violet-600 to-purple-700'],
  ['T├¡ch xanh Facebook c├í nh├ón', 'from-blue-600 to-blue-700'],
  ['T├¡ch xanh TikTok', 'from-slate-800 to-slate-950'],
  ['T├¡ch xanh Instagram', 'from-pink-600 to-rose-700']
];
</script>

<template>
  <div class="site-page min-h-screen bg-[#f7fbff] text-slate-950 transition-colors duration-300 dark:bg-[#07101f] dark:text-white">
    <header class="site-header mx-auto flex max-w-[760px] items-center justify-between px-5 py-5">
      <a href="/" class="site-logo flex items-center gap-2 text-[13px] font-black text-slate-900 dark:text-white">
        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white">
          <Check class="h-3.5 w-3.5 stroke-[4]" />
        </span>
        <span>─É─âng K├╜ T├¡ch Xanh<small>.vn</small></span>
      </a>
      <nav class="site-nav">
        <a href="#">Trang chß╗º</a>
        <div class="site-service-menu">
          <button type="button">Dß╗ïch vß╗Ñ <ChevronDown class="site-service-chevron" /></button>
          <div class="site-service-dropdown">
            <a v-for="service in servicePages" :key="service.id" :href="`/${service.id}`" @click.prevent="openServicePage(service)">{{ service.name }}</a>
          </div>
        </div>
        <a href="#loi-ich">Lß╗úi ├¡ch</a>
        <a href="#bang-gia">Bß║úng gi├í</a>
        <a href="#quy-trinh">Quy tr├¼nh</a>
        <a href="#tin-tuc">Tin tß╗⌐c</a>
        <a href="#contact">Li├¬n hß╗ç</a>
      </nav>
      <div class="site-actions flex items-center gap-3">
        <a class="site-fast-register" href="#contact">≡ƒÜÇ ─É─âng k├╜ nhanh</a>
        <button class="site-theme-button text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white" @click="applyTheme(!isDark)" aria-label="─Éß╗òi giao diß╗çn">
          <Sun v-if="isDark" class="h-4 w-4" />
          <Moon v-else class="h-4 w-4" />
        </button>
        <a class="site-header-phone" href="tel:0968825068">0968.825.068</a>
        <Menu class="site-menu-icon h-4 w-4 text-slate-500 dark:text-slate-400" />
      </div>
    </header>

    <main v-if="selectedArticle" class="article-modern">
      <div class="article-modern-shell">
        <div class="article-modern-wrap">
          <nav class="article-breadcrumb" aria-label="─É╞░ß╗¥ng dß║½n b├ái viß║┐t">
            <button type="button" @click="closeArticle">Trang chß╗º</button>
            <span>/</span>
            <button type="button" @click="closeArticle">Tin tß╗⌐c</button>
            <span>/</span>
            <span>{{ selectedArticle.title }}</span>
          </nav>

          <div class="article-modern-grid">
            <article class="article-modern-main">
              <h1>{{ selectedArticle.title }}</h1>

              <div class="article-tldr">
                <strong>≡ƒÆí TL;DR - T├ôM Tß║«T NHANH</strong>
                <p>{{ selectedArticle.description }}</p>
              </div>

              <div class="article-modern-meta">
                <span>{{ selectedArticle.author || '─É─âng K├╜ T├¡ch Xanh Team' }}</span>
                <span>┬╖</span>
                <span>{{ selectedArticle.category }}</span>
                <span>┬╖</span>
                <span>{{ selectedArticle.date }}</span>
                <span>┬╖</span>
                <span>{{ selectedArticle.readTime }}</span>
                <button type="button">L╞░u b├ái</button>
              </div>

              <div class="article-hero-img">
                <img :src="selectedArticle.banner" :alt="selectedArticle.title" />
              </div>

              <div class="article-modern-content" v-html="selectedArticle.content"></div>
            </article>

            <aside class="article-modern-sidebar">
              <h3>Γÿ╖ Mß╗Ñc lß╗Ñc</h3>
              <a v-for="item in articles.filter(a => a.id !== selectedArticle.id).slice(0, 6)" :key="item.id" href="#" @click.prevent="openArticle(item)">
                {{ item.title }}
              </a>
              <a href="#contact" @click="selectedArticle = null">Dß╗ïch vß╗Ñ hß╗ù trß╗ú ─æ─âng k├╜ t├¡ch xanh</a>
            </aside>
          </div>
        </div>
      </div>
    </main>

    <!-- BLOG PAGE -->
    <main v-else-if="selectedPage === 'blog'" class="min-h-screen bg-[#f7fbff] dark:bg-[#07101f]">
      <div class="mx-auto max-w-[760px] px-5 py-12">
        <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
          <button @click="closePage" class="hover:text-blue-600">Trang chß╗º</button>
          <span>/</span><span class="text-slate-900 dark:text-white">Tin tß╗⌐c & Kiß║┐n thß╗⌐c</span>
        </nav>
        <h1 class="text-3xl font-black text-slate-950 dark:text-white">Tin Tß╗⌐c & Kiß║┐n Thß╗⌐c</h1>
        <p class="mt-2 text-[13px] font-medium text-slate-500 dark:text-slate-400">Cß║¡p nhß║¡t mß╗¢i nhß║Ñt vß╗ü ─æ─âng k├╜ t├¡ch xanh Facebook, Instagram, TikTok & WhatsApp</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
          <article v-for="item in articles" :key="item.id"
            class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-white/10 dark:bg-[#111d34]"
            @click="openArticle(item)">
            <img :src="item.banner" :alt="item.title" class="mb-4 h-36 w-full rounded-lg object-cover" loading="lazy" />
            <span class="inline-block rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">{{ item.category }}</span>
            <h2 class="mt-2 text-[13px] font-black leading-snug text-slate-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">{{ item.title }}</h2>
            <p class="mt-1.5 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400 line-clamp-2">{{ item.description }}</p>
            <div class="mt-3 flex items-center gap-2 text-[10px] font-semibold text-slate-400">
              <span>{{ item.author }}</span><span>┬╖</span><span>{{ item.date }}</span><span>┬╖</span><span>{{ item.readTime }}</span>
            </div>
          </article>
        </div>
        <div class="mt-10 rounded-xl bg-blue-600 p-6 text-center text-white">
          <p class="text-sm font-black">Cß║ºn hß╗ù trß╗ú ─æ─âng k├╜ t├¡ch xanh?</p>
          <a href="tel:0968825068" class="mt-3 inline-block rounded-lg bg-white px-5 py-2 text-[12px] font-black text-blue-600">Gß╗ìi 0968.825.068</a>
        </div>
      </div>
    </main>

    <!-- GIß╗ÜI THIß╗åU PAGE -->
    <main v-else-if="selectedPage === 'gioi-thieu'" class="min-h-screen bg-[#f7fbff] dark:bg-[#07101f]">
      <div class="mx-auto max-w-[760px] px-5 py-12">
        <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
          <button @click="closePage" class="hover:text-blue-600">Trang chß╗º</button>
          <span>/</span><span class="text-slate-900 dark:text-white">Giß╗¢i thiß╗çu</span>
        </nav>
        <h1 class="text-3xl font-black text-slate-950 dark:text-white">Vß╗ü Ch├║ng T├┤i</h1>
        <p class="mt-3 text-[13px] font-medium leading-6 text-slate-600 dark:text-slate-300">
          <strong>AZ Media</strong> l├á ─æ╞ín vß╗ï chuy├¬n t╞░ vß║Ñn v├á hß╗ù trß╗ú ─æ─âng k├╜ t├¡ch xanh (Meta Verified) cho Fanpage Facebook, Instagram, TikTok v├á WhatsApp tß║íi Viß╗çt Nam. Vß╗¢i 5+ n─âm kinh nghiß╗çm, ch├║ng t├┤i ─æ├ú hß╗ù trß╗ú h╞ín <strong>2.368 kh├ích h├áng</strong> th├ánh c├┤ng.
        </p>
        <div class="mt-8 grid gap-4 sm:grid-cols-3">
          <div v-for="[num, label] in [['2.368+', 'Kh├ích h├áng'], ['100%', 'Tß╗ë lß╗ç th├ánh c├┤ng'], ['5+', 'N─âm kinh nghiß╗çm']]" :key="label"
            class="rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm dark:border-white/10 dark:bg-[#111d34]">
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ num }}</p>
            <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ label }}</p>
          </div>
        </div>
        <div class="mt-8 space-y-4 text-[13px] font-medium leading-6 text-slate-600 dark:text-slate-300">
          <h2 class="text-xl font-black text-slate-950 dark:text-white">Cam kß║┐t cß╗ºa ch├║ng t├┤i</h2>
          <ul class="space-y-3">
            <li v-for="item in ['L├ám xong t├¡ch xanh mß╗¢i thu ph├¡ ΓÇö kh├┤ng mß║Ñt tiß╗ün oan', 'Kh├┤ng y├¬u cß║ºu mß║¡t khß║⌐u, ─æß║úm bß║úo an to├án t├ái khoß║ún', 'Hß╗ù trß╗ú 24/7, bß║úo h├ánh trß╗ìn ─æß╗¥i', 'Chß╗ë d├╣ng ph╞░╞íng ph├íp ch├¡nh thß╗⌐c cß╗ºa Meta']" :key="item"
              class="flex items-start gap-2">
              <span class="mt-0.5 text-emerald-500">Γ£ô</span>{{ item }}
            </li>
          </ul>
          <h2 class="mt-6 text-xl font-black text-slate-950 dark:text-white">Th├┤ng tin c├┤ng ty</h2>
          <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-[#111d34] space-y-2 text-[12px]">
            <p><strong>M├ú sß╗æ doanh nghiß╗çp:</strong> 0314247738 ΓÇô 002</p>
            <p><strong>─Éß╗ïa chß╗ë ─ÉKKD:</strong> 127 L├¬ Trß╗ìng Tß║Ñn, Ph╞░ß╗¥ng An Kh├¬, ─É├á Nß║╡ng</p>
            <p><strong>V─ân ph├▓ng giao dß╗ïch:</strong> 84 Nguyß╗àn Hß╗»u Dß║¡t, H├▓a C╞░ß╗¥ng Bß║»c, Hß║úi Ch├óu, ─É├á Nß║╡ng</p>
            <p><strong>─Éiß╗çn thoß║íi:</strong> <a href="tel:0968825068" class="text-blue-600 dark:text-blue-400">0968.825.068</a> (Mr. Quang ΓÇô CEO)</p>
            <p><strong>Email:</strong> <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-600 dark:text-blue-400">azmedia.com.vn@gmail.com</a></p>
            <p><strong>Facebook:</strong> <a href="https://fb.com/congtyazmedia" target="_blank" class="text-blue-600 dark:text-blue-400">fb.com/congtyazmedia</a></p>
          </div>
        </div>
      </div>
    </main>

    <!-- LEGAL PAGES -->
    <main v-else-if="selectedPage && selectedPage.startsWith('legal-')" class="min-h-screen bg-[#f7fbff] dark:bg-[#07101f]">
      <div class="mx-auto max-w-[760px] px-5 py-12">
        <nav class="mb-6 flex items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
          <button @click="closePage" class="hover:text-blue-600">Trang chß╗º</button>
          <span>/</span>
          <span class="text-slate-900 dark:text-white">{{ selectedPage === 'legal-terms' ? '─Éiß╗üu khoß║ún dß╗ïch vß╗Ñ' : selectedPage === 'legal-privacy' ? 'Ch├¡nh s├ích bß║úo mß║¡t' : 'DMCA' }}</span>
        </nav>
        <!-- TERMS -->
        <template v-if="selectedPage === 'legal-terms'">
          <h1 class="text-3xl font-black text-slate-950 dark:text-white">─Éiß╗üu Khoß║ún Dß╗ïch Vß╗Ñ</h1>
          <div class="mt-6 space-y-5 text-[13px] leading-6 text-slate-600 dark:text-slate-300">
            <p>Bß║▒ng viß╗çc sß╗¡ dß╗Ñng dß╗ïch vß╗Ñ cß╗ºa <strong>─É─âng K├╜ T├¡ch Xanh (AZ Media)</strong>, bß║ín ─æß╗ông ├╜ vß╗¢i c├íc ─æiß╗üu khoß║ún sau:</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">1. Phß║ím vi dß╗ïch vß╗Ñ</h2>
            <p>Ch├║ng t├┤i cung cß║Ñp dß╗ïch vß╗Ñ t╞░ vß║Ñn v├á hß╗ù trß╗ú ─æ─âng k├╜ t├¡ch xanh (Meta Verified) cho Fanpage Facebook, Instagram, TikTok v├á WhatsApp Business th├┤ng qua c├íc ph╞░╞íng ph├íp ch├¡nh thß╗⌐c cß╗ºa Meta.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">2. Ch├¡nh s├ích thanh to├ín</h2>
            <p>Kh├ích h├áng <strong>chß╗ë thanh to├ín sau khi t├¡ch xanh ─æ├ú hiß╗ân thß╗ï th├ánh c├┤ng</strong> tr├¬n t├ái khoß║ún. Kh├┤ng thu bß║Ñt kß╗│ khoß║ún tiß╗ün n├áo tr╞░ß╗¢c khi ho├án th├ánh.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">3. Bß║úo h├ánh & hß╗ù trß╗ú</h2>
            <p>Ch├║ng t├┤i cam kß║┐t hß╗ù trß╗ú trß╗ìn ─æß╗¥i trong tr╞░ß╗¥ng hß╗úp t├¡ch xanh bß╗ï mß║Ñt do lß╗ùi kß╗╣ thuß║¡t tß╗½ ph├¡a ch├║ng t├┤i.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">4. Giß╗¢i hß║ín tr├ích nhiß╗çm</h2>
            <p>Ch├║ng t├┤i kh├┤ng chß╗ïu tr├ích nhiß╗çm cho c├íc thay ─æß╗òi ch├¡nh s├ích ─æß╗Öt ngß╗Öt tß╗½ ph├¡a Meta/Facebook l├ám ß║únh h╞░ß╗ƒng ─æß║┐n t├¡ch xanh sau khi ─æ├ú ho├án th├ánh dß╗ïch vß╗Ñ.</p>
          </div>
        </template>
        <!-- PRIVACY -->
        <template v-else-if="selectedPage === 'legal-privacy'">
          <h1 class="text-3xl font-black text-slate-950 dark:text-white">Ch├¡nh S├ích Bß║úo Mß║¡t</h1>
          <div class="mt-6 space-y-5 text-[13px] leading-6 text-slate-600 dark:text-slate-300">
            <p>Ch├║ng t├┤i cam kß║┐t bß║úo vß╗ç th├┤ng tin c├í nh├ón cß╗ºa kh├ích h├áng theo c├íc nguy├¬n tß║»c sau:</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">1. Th├┤ng tin thu thß║¡p</h2>
            <p>Ch├║ng t├┤i chß╗ë thu thß║¡p: Hß╗ì t├¬n, sß╗æ ─æiß╗çn thoß║íi, link Fanpage/t├ái khoß║ún cß║ºn ─æ─âng k├╜. <strong>Tuyß╗çt ─æß╗æi kh├┤ng y├¬u cß║ºu mß║¡t khß║⌐u.</strong></p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">2. Mß╗Ñc ─æ├¡ch sß╗¡ dß╗Ñng</h2>
            <p>Th├┤ng tin chß╗ë ─æ╞░ß╗úc d├╣ng ─æß╗â li├¬n hß╗ç t╞░ vß║Ñn v├á thß╗▒c hiß╗çn dß╗ïch vß╗Ñ. Kh├┤ng chia sß║╗ cho b├¬n thß╗⌐ ba.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">3. Bß║úo mß║¡t dß╗» liß╗çu</h2>
            <p>Th├┤ng tin kh├ích h├áng ─æ╞░ß╗úc l╞░u trß╗» an to├án v├á x├│a sau khi ho├án th├ánh dß╗ïch vß╗Ñ theo y├¬u cß║ºu.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">4. Li├¬n hß╗ç</h2>
            <p>Mß╗ìi thß║»c mß║»c vß╗ü bß║úo mß║¡t: <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-600 dark:text-blue-400">azmedia.com.vn@gmail.com</a></p>
          </div>
        </template>
        <!-- DMCA -->
        <template v-else>
          <h1 class="text-3xl font-black text-slate-950 dark:text-white">DMCA</h1>
          <div class="mt-6 space-y-5 text-[13px] leading-6 text-slate-600 dark:text-slate-300">
            <p>Tß║Ñt cß║ú nß╗Öi dung tr├¬n website <strong>dangkytichxanh.vn</strong> bao gß╗ôm v─ân bß║ún, h├¼nh ß║únh, logo v├á thiß║┐t kß║┐ ─æß╗üu thuß╗Öc quyß╗ün sß╗ƒ hß╗»u cß╗ºa AZ Media.</p>
            <h2 class="text-base font-black text-slate-900 dark:text-white">B├ío c├ío vi phß║ím bß║ún quyß╗ün</h2>
            <p>Nß║┐u bß║ín ph├ít hiß╗çn nß╗Öi dung cß╗ºa ch├║ng t├┤i bß╗ï sao ch├⌐p tr├íi ph├⌐p, vui l├▓ng li├¬n hß╗ç:</p>
            <ul class="space-y-1 pl-4">
              <li>Email: <a href="mailto:azmedia.com.vn@gmail.com" class="text-blue-600 dark:text-blue-400">azmedia.com.vn@gmail.com</a></li>
              <li>Hotline: <a href="tel:0968825068" class="text-blue-600 dark:text-blue-400">0968.825.068</a></li>
            </ul>
            <p>Ch├║ng t├┤i sß║╜ xß╗¡ l├╜ trong v├▓ng 72 giß╗¥ l├ám viß╗çc.</p>
          </div>
        </template>
      </div>
    </main>

    <main v-else>
      <section class="mx-auto grid max-w-[760px] gap-8 px-5 pb-14 pt-7 md:grid-cols-[1.05fr_0.95fr] md:items-center md:pt-14">
        <div>
          <div class="mb-5 inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
            {{ currentHero.badge }}
          </div>
          <h1 class="max-w-[360px] text-[39px] font-black leading-[0.95] tracking-tight text-slate-950 dark:text-white md:text-[43px]">
            {{ currentHero.title }}
          </h1>
          <p class="mt-4 max-w-[390px] text-[13px] font-medium leading-6 text-slate-600 dark:text-slate-300">
            {{ currentHero.desc }}
          </p>

          <div class="mt-4 grid max-w-[390px] grid-cols-2 gap-2">
<div v-for="item in currentHero.features" :key="item" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-semibold leading-4 text-slate-700 shadow-sm dark:border-white/10 dark:bg-[#0e1b31] dark:text-slate-200">
              <Check class="mb-1 h-3.5 w-3.5 text-emerald-500" />
              {{ item }}
            </div>
          </div>

          <div class="mt-5 flex flex-wrap gap-3">
            <a href="#contact" class="rounded-lg bg-blue-600 px-5 py-3 text-[12px] font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500">─É─âng K├╜ Ngay</a>
            <a href="tel:0968825068" class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-[12px] font-black text-slate-800 shadow-sm hover:border-blue-300 dark:border-white/10 dark:bg-[#0e1b31] dark:text-white">0968.825.068</a>
          </div>
        </div>

        <div class="mx-auto w-full max-w-[330px] rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl shadow-slate-900/8 dark:border-white/10 dark:bg-[#14223b] dark:shadow-none">
          <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 text-2xl font-black text-white">DK</div>
          <div class="mt-4 flex items-center justify-center gap-1 text-sm font-black text-slate-950 dark:text-white">
            ─É─âng K├╜ T├¡ch Xanh
            <BadgeCheck class="h-4 w-4 fill-blue-600 text-white" />
          </div>
          <p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">Meta Verified ┬╖ Trang ch├¡nh chß╗º</p>
          <div class="mt-5 grid grid-cols-3 border-t border-slate-100 pt-4 text-center dark:border-white/10">
            <div v-for="stat in [['2.368', 'Trang ─æ├ú cß║Ñp'], ['99%', 'Tß╗╖ lß╗ç th├ánh c├┤ng'], ['15ΓÇÖ', 'Thß╗¥i gian']]" :key="stat[1]">
              <p class="text-sm font-black text-blue-600 dark:text-blue-400">{{ stat[0] }}</p>
              <p class="mt-1 text-[9px] font-semibold text-slate-500 dark:text-slate-400">{{ stat[1] }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-[#092247] py-7 text-white">
        <div class="mx-auto grid max-w-[760px] grid-cols-4 px-5 text-center">
          <div v-for="item in stats" :key="item[1]">
            <p class="text-xl font-black">{{ item[0] }}</p>
            <p class="mt-1 text-[10px] font-semibold text-blue-100/80">{{ item[1] }}</p>
          </div>
        </div>
      </section>

      <section class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mx-auto max-w-[560px] text-center">
            <h2 class="text-2xl font-black leading-tight text-slate-950 dark:text-white">T├¡ch Xanh ─Éem Lß║íi G├¼ Cho Fanpage & Business Cß╗ºa Bß║ín?</h2>
<p class="mt-3 text-[12px] font-medium text-slate-500 dark:text-slate-400">T├¡n hiß╗çu Uy T├¡n cao nhß║Ñt tr├¬n Meta, t─âng tiß║┐p cß║¡n tß╗▒ nhi├¬n v├á doanh sß╗æ ngay lß║¡p tß╗⌐c</p>
          </div>
          <div class="mt-10 grid gap-4 sm:grid-cols-2">
            <div v-for="[Icon, title, desc] in benefits" :key="title" class="rounded-xl border border-slate-200 bg-white p-7 text-center shadow-sm dark:border-white/10 dark:bg-[#122039]">
              <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                <component :is="Icon" class="h-5 w-5" />
              </div>
              <h3 class="mt-5 text-sm font-black text-slate-950 dark:text-white">{{ title }}</h3>
              <p class="mt-2 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <section id="bang-gia" class="bg-[#f3f8ff] py-16 dark:bg-[#0b1528]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-9 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Bß║úng gi├í dß╗ïch vß╗Ñ</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">L├ám xong mß╗¢i thanh to├ín, cam kß║┐t 100% th├ánh c├┤ng</p>
          </div>
          <div class="grid gap-4 md:grid-cols-3">
            <article v-for="card in priceCards" :key="card.name" class="relative rounded-xl border bg-white p-5 shadow-sm dark:bg-[#111d34]" :class="card.popular ? 'border-blue-500 ring-2 ring-blue-500/20 dark:border-blue-400' : 'border-slate-200 dark:border-white/10'">
              <span v-if="card.popular" class="absolute -top-3 right-4 rounded-full bg-emerald-400 px-3 py-1 text-[10px] font-black text-white">Phß╗ò biß║┐n nhß║Ñt</span>
              <h3 class="text-sm font-black text-slate-950 dark:text-white">{{ card.name }}</h3>
              <p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ card.desc }}</p>
              <p class="mt-5 text-2xl font-black text-blue-600 dark:text-blue-400">{{ card.price }}</p>
              <p class="text-[10px] font-bold text-slate-400">trß╗ìn g├│i</p>
              <ul class="mt-5 space-y-2">
                <li v-for="feature in card.features" :key="feature" class="flex gap-2 text-[10px] font-semibold leading-4 text-slate-600 dark:text-slate-300">
                  <Check class="h-3.5 w-3.5 shrink-0 text-emerald-500" />
                  {{ feature }}
                </li>
              </ul>
              <div class="mt-5 rounded-lg bg-slate-50 px-3 py-2 text-[9px] font-semibold text-slate-500 dark:bg-[#0a1325] dark:text-slate-400">Ph├¡ Meta Verified: t╞░ vß║Ñn khi kiß╗âm tra hß╗ô s╞í</div>
<a href="#contact" class="mt-5 block rounded-lg border border-blue-600 px-4 py-2.5 text-center text-[11px] font-black text-blue-600 hover:bg-blue-600 hover:text-white dark:text-blue-300">{{ card.button }}</a>
            </article>
          </div>
        </div>
      </section>

      <section id="quy-trinh" class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[560px] px-5">
          <div class="text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Quy tr├¼nh 4 b╞░ß╗¢c</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">─É╞ín giß║ún, minh bß║ích, ho├án th├ánh trong 15 ph├║t</p>
          </div>
          <div class="mt-10 space-y-6">
            <div v-for="step in process" :key="step[0]" class="flex gap-5">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-black text-white shadow-lg shadow-blue-600/25">{{ step[0] }}</div>
              <div class="pt-1">
                <h3 class="text-sm font-black text-slate-950 dark:text-white">{{ step[1] }}</h3>
                <p class="mt-1 text-[12px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ step[2] }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="loi-ich" class="why-choice-section bg-[#0c1930] py-16 text-white dark:bg-[#0c1930]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="why-choice-heading mb-9 text-center">
            <h2 class="text-2xl font-black">Tß║íi sao chß╗ìn ch├║ng t├┤i?</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-300">─É╞░ß╗úc 2.368 kh├ích h├áng tin t╞░ß╗ƒng</p>
          </div>
          <div class="why-choice-grid grid gap-4 sm:grid-cols-2">
            <div v-for="[Icon, title, desc] in reasons" :key="title" class="why-choice-card rounded-xl bg-white/7 p-6 text-center">
              <div class="why-choice-icon mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/20 text-cyan-300">
                <component :is="Icon" class="h-5 w-5" />
              </div>
              <h3 class="mt-4 text-sm font-black">{{ title }}</h3>
              <p class="mt-2 text-[11px] font-medium leading-5 text-slate-300">{{ desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <h2 class="mb-8 text-center text-2xl font-black text-slate-950 dark:text-white">Kh├ích h├áng n├│i g├¼?</h2>
          <div class="grid gap-4 md:grid-cols-3">
            <div v-for="review in reviews" :key="review[0]" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#111d34]">
              <div class="mb-3 flex text-amber-400">
<Star v-for="n in 5" :key="n" class="h-3.5 w-3.5 fill-current" />
              </div>
              <p class="min-h-20 text-[11px] font-medium italic leading-5 text-slate-600 dark:text-slate-300">"{{ review[3] }}"</p>
              <div class="mt-5 flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-[10px] font-black text-white">{{ review[2] }}</div>
                <div>
                  <p class="text-[11px] font-black text-slate-950 dark:text-white">{{ review[0] }}</p>
                  <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400">{{ review[1] }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-[#f8fbff] py-16 dark:bg-[#0b1528]">
        <div class="mx-auto max-w-[640px] px-5">
          <h2 class="mb-8 text-center text-2xl font-black leading-tight text-slate-950 dark:text-white">─É─âng K├╜ T├¡ch Xanh C├│ An To├án Kh├┤ng? <br />- Giß║úi ─É├íp 8 C├óu Hß╗Åi Top</h2>
          <div class="space-y-3">
            <div v-for="(faq, index) in faqs" :key="faq[0]" class="rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-[#111d34]">
              <button class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-[12px] font-black text-slate-900 dark:text-white" @click="activeFaq = activeFaq === index ? -1 : index">
                {{ faq[0] }}
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                  <ChevronDown class="h-3.5 w-3.5 transition" :class="{ 'rotate-180': activeFaq === index }" />
                </span>
              </button>
              <p v-if="activeFaq === index" class="px-4 pb-4 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ faq[1] }}</p>
            </div>
          </div>
        </div>
      </section>

      <section id="tin-tuc" class="bg-[#eef6ff] py-16 dark:bg-[#0d1b31]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-8 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">B├ái viß║┐t hß╗»u ├¡ch</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Kiß║┐n thß╗⌐c cß║¡p nhß║¡t tr╞░ß╗¢c khi ─æ─âng k├╜ t├¡ch xanh</p>
          </div>
          <div class="grid gap-3 sm:grid-cols-2">
            <button v-for="item in articles" :key="item.id" type="button" class="flex items-center justify-between rounded-lg bg-white px-4 py-3 text-left text-[12px] font-bold text-slate-700 shadow-sm dark:bg-[#111d34] dark:text-slate-200" @click="openArticle(item)">
              <span>{{ item.title }}</span>
              <ChevronDown class="-rotate-90 h-3.5 w-3.5" />
            </button>
          </div>
<div class="mt-5 grid gap-3 sm:grid-cols-2">
            <a v-for="item in quickServices" :key="item[0]" href="#contact" class="flex items-center justify-between rounded-lg bg-gradient-to-r px-4 py-3 text-[12px] font-black text-white" :class="item[1]">
              {{ item[0] }}
              <ChevronDown class="-rotate-90 h-4 w-4" />
            </a>
          </div>
        </div>
      </section>

      <section class="bg-blue-600 py-14 text-center text-white">
        <div class="mx-auto max-w-[760px] px-5">
          <h2 class="text-2xl font-black">Sß║╡n s├áng c├│ t├¡ch xanh trong 15 ph├║t?</h2>
          <p class="mt-3 text-[12px] font-semibold text-blue-100">Hotline 0968.825.068 - t╞░ vß║Ñn miß╗àn ph├¡, kh├┤ng ├⌐p mua, hß╗ù trß╗ú cß║ú ─æ├¬m</p>
          <div class="mt-6 flex justify-center gap-3">
            <a href="#contact" class="rounded-lg bg-white px-5 py-3 text-[12px] font-black text-blue-600">─É─âng k├╜ ngay</a>
            <a href="tel:0968825068" class="rounded-lg border border-white/70 px-5 py-3 text-[12px] font-black text-white">0968.825.068</a>
          </div>
        </div>
      </section>

      <section id="contact" class="bg-[#f8fbff] py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-8 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Li├¬n hß╗ç</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Ch├║ng t├┤i trß║ú lß╗¥i trong 15 ph├║t</p>
          </div>
          <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-3">
              <div v-for="[Icon, label, value, href] in [[Phone, 'HOTLINE', '0968.825.068 (Mr. Quang)', 'tel:0968825068'], [MessageCircle, 'ZALO', '0968.825.068', 'https://zalo.me/0968825068'], [Mail, 'EMAIL', 'azmedia.com.vn@gmail.com', 'mailto:azmedia.com.vn@gmail.com'], [MapPin, '─Éß╗èA CHß╗ê', '84 Nguyß╗àn Hß╗»u Dß║¡t, H├▓a C╞░ß╗¥ng Bß║»c, Hß║úi Ch├óu, ─É├á Nß║╡ng', null], [Clock, 'L├ÇM VIß╗åC', '8:00 - 12:00, 13:30 - 17:30', null]]" :key="label" class="contact-info-card flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#111d34]">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                  <component :is="Icon" class="h-4 w-4" />
                </div>
                <div>
                  <p class="text-[10px] font-black text-slate-400">{{ label }}</p>
                  <a v-if="href" :href="href" class="text-[12px] font-black text-slate-900 hover:text-blue-600 dark:text-white">{{ value }}</a>
                  <p v-else class="text-[12px] font-black text-slate-900 dark:text-white">{{ value }}</p>
                </div>
              </div>
            </div>
            <form class="contact-form-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#111d34]" @submit.prevent="submitLead">
              <h3 class="text-sm font-black text-slate-950 dark:text-white">─É─âng k├╜ t╞░ vß║Ñn miß╗àn ph├¡</h3>
              <p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">─Éiß╗ün 3 th├┤ng tin, ch├║ng t├┤i gß╗ìi lß║íi trong 15 ph├║t</p>
              <div v-if="formSuccess" class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-center text-[12px] font-bold text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300">
                Γ£à ─É├ú gß╗¡i! Ch├║ng t├┤i sß║╜ gß╗ìi lß║íi trong 15 ph├║t.
              </div>
              <div v-else class="mt-4 space-y-3">
                <label class="contact-label">Hß╗ì t├¬n *</label>
                <input v-model="leadName" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="Nguyß╗àn V─ân A" />
                <label class="contact-label">Sß╗æ ─æiß╗çn thoß║íi *</label>
                <input v-model="leadPhone" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="0912345678" />
                <label class="contact-label">Lß╗¥i nhß║»n</label>
                <textarea v-model="leadLink" class="min-h-20 w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="VD: T├┤i muß╗æn ─æ─âng k├╜ t├¡ch xanh cho Fanpage ABC..."></textarea>
                <div v-if="formError" class="text-[11px] text-red-500 font-semibold">ΓÜá∩╕Å Vui l├▓ng ─æiß╗ün ─æß║ºy ─æß╗º th├┤ng tin hoß║╖c thß╗¡ lß║íi.</div>
                <button :disabled="formLoading" class="w-full rounded-lg bg-blue-600 py-3 text-[12px] font-black text-white hover:bg-blue-500 disabled:opacity-60 disabled:cursor-not-allowed">
                  {{ formLoading ? '─Éang gß╗¡i...' : 'Gß╗¡i y├¬u cß║ºu t╞░ vß║Ñn' }}
                </button>
                <p class="text-center text-[10px] font-medium text-slate-400">Cam kß║┐t bß║úo mß║¡t th├┤ng tin, kh├┤ng spam</p>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-[#07101f] py-10 text-slate-400 dark:bg-[#092247]">
      <div class="mx-auto grid max-w-[760px] gap-8 px-5 md:grid-cols-[1.2fr_0.8fr_0.8fr]">
        <div>
          <div class="flex items-center gap-2 text-[13px] font-black text-white">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white"><Check class="h-3.5 w-3.5 stroke-[4]" /></span>
            ─É─âng K├╜ T├¡ch Xanh
          </div>
          <p class="mt-4 text-[11px] font-medium leading-5">─É─âng k├╜ t├¡ch xanh Facebook, Instagram 15 ph├║t. Kh├┤ng cß║ºn b├ío ch├¡, l├ám xong thanh to├ín. Dß╗ïch vß╗Ñ hß╗ù trß╗ú bß╗ƒi AZ Media.</p>
        </div>
        <div>
          <h3 class="text-[12px] font-black text-white">Dß╗ïch vß╗Ñ</h3>
          <ul class="mt-3 space-y-2 text-[11px] font-medium">
            <li>T├¡ch xanh Fanpage</li>
            <li>T├¡ch xanh Instagram</li>
            <li>T├¡ch xanh TikTok</li>
            <li>T├¡ch xanh C├í nh├ón</li>
          </ul>
        </div>
        <div>
<h3 class="text-[12px] font-black text-white">Li├¬n hß╗ç</h3>
          <ul class="mt-3 space-y-2 text-[11px] font-medium">
            <li>Hotline: 0968.825.068</li>
            <li>Email: azmedia.com.vn@gmail.com</li>
            <li>84 Nguyß╗àn Hß╗»u Dß║¡t, H├▓a C╞░ß╗¥ng Bß║»c, Hß║úi Ch├óu, ─É├á Nß║╡ng</li>
          </ul>
        </div>
      </div>
      <div class="mx-auto mt-8 flex max-w-[760px] flex-wrap justify-between gap-3 border-t border-white/10 px-5 pt-5 text-[10px] font-medium">
        <p>┬⌐ 2026 ─É─âng K├╜ T├¡ch Xanh - Dß╗ïch vß╗Ñ ─æ─âng k├╜ t├¡ch xanh Facebook, Fanpage, Instagram, TikTok & WhatsApp.</p>
        <p class="flex gap-3">
          <a href="/gioi-thieu" @click.prevent="openPage('gioi-thieu')" class="hover:text-white transition-colors">Giß╗¢i thiß╗çu</a>
          <a href="/legal/terms" @click.prevent="openPage('legal-terms')" class="hover:text-white transition-colors">─Éiß╗üu khoß║ún</a>
          <a href="/legal/privacy" @click.prevent="openPage('legal-privacy')" class="hover:text-white transition-colors">Bß║úo mß║¡t</a>
          <a href="/legal/dmca" @click.prevent="openPage('legal-dmca')" class="hover:text-white transition-colors">DMCA</a>
        </p>
      </div>
    </footer>

    <a href="tel:0968825068" class="fixed right-5 top-[300px] z-40 flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl shadow-blue-600/30">
      <MessageCircle class="h-5 w-5" />
    </a>
  </div>
</template>






